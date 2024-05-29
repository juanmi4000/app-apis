<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstagramController extends Controller
{
    private $accessToken;
    private $igUserId;
    private $httpClient;

    public function __construct()
    {
        $this->accessToken = env('INSTAGRAM_ACCESS_TOKEN');
        $this->igUserId = env('INSTAGRAM_USER_ID');
        $this->httpClient = new Client();
    }

    public function index()
    {
        return view('instagram.index');
    }

    public function programarPublicacion(Request $request)
{
    // Recibir la fecha y la hora desde la solicitud
    $fecha = $request->input('fecha');
    $hora = $request->input('hora');

    // Validar la fecha y la hora (puedes agregar tus propias validaciones aquí)
    if (!$this->validateFechaHora($fecha, $hora)) {
        return response()->json(['error' => 'La fecha u hora proporcionadas no son válidas.'], 400);
    }

    // Formatear la fecha y la hora en un formato compatible con la API de Instagram
    $timestamp = strtotime("$fecha $hora");
    $instagramDateTime = date('Y-m-d\TH:i:s', $timestamp);

    // Lógica para programar la publicación en Instagram
    $result = $this->programarPublicacionInstagram($instagramDateTime);

    if ($result) {
        return response()->json(['success' => 'Publicación programada con éxito.']);
    } else {
        return response()->json(['error' => 'Error al programar la publicación en Instagram.'], 500);
    }
}

private function validateFechaHora($fecha, $hora)
{
    // Verificar si la fecha y hora están en un formato válido
    if (!strtotime("$fecha $hora")) {
        return false; // Si no se puede convertir a un timestamp, la fecha u hora no son válidas
    }

    // Verificar si la fecha y hora están en el futuro
    $timestamp = strtotime("$fecha $hora");
    if ($timestamp <= time()) {
        return false; // Si la fecha y hora son anteriores al momento actual, no son válidas
    }

    return true; // Si pasa todas las validaciones, la fecha y hora son válidas
}

private function programarPublicacionInstagram($instagramDateTime)
{
    try {
        // Aquí debes realizar la lógica para interactuar con la API de Instagram y programar la publicación
        // Esto puede implicar realizar solicitudes HTTP a la API de Instagram utilizando el token de acceso y la fecha/hora proporcionados

        // Por ahora, simularemos el éxito programando la publicación
        // Puedes reemplazar este código con la lógica real para interactuar con la API de Instagram
        Log::info('Publicación programada en Instagram para: ' . $instagramDateTime);
        return true;
    } catch (\Exception $e) {
        Log::error('Error al programar la publicación en Instagram', ['exception' => $e]);
        return false;
    }
}




    public function uploadImage(Request $request)
{
    $request->validate([
        'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('imagen')) {
        $imagePath = $request->file('imagen')->store('uploads');

        // Obtener la ruta completa del archivo
        $imageFullPath = storage_path('app/' . $imagePath);

        // Verificar si el archivo existe antes de continuar
        if (file_exists($imageFullPath)) {

            // Loggear la subida a almacenamiento local
            Log::info('Image uploaded to local storage', ['path' => $imagePath, 'url' => Storage::url($imagePath)]);

            // Subir la imagen a ImgBB
            $imgbbUrl = $this->uploadImageAndGetUrl($imageFullPath);

            if ($imgbbUrl) {
                // Loggear la subida a ImgBB
                Log::info('Image uploaded to ImgBB', ['url' => $imgbbUrl]);

                // Intentar publicar la imagen en Instagram
                $instagramResponse = $this->postImageToInstagram($imgbbUrl);

                if ($instagramResponse) {
                    // Loggear la publicación en Instagram
                    Log::info('Image posted to Instagram');
                    return redirect()->back()->with('success', 'La imagen se ha subido correctamente a ImgBB y a Instagram.');
                } else {
                    // Error al publicar en Instagram
                    Log::error('Failed to post image to Instagram');
                }
            } else {
                // Error al subir a ImgBB
                Log::error('Failed to upload image to ImgBB');
            }
        } else {
            // Manejar el caso donde el archivo no existe
            Log::error('Uploaded image not found at: ' . $imageFullPath);
        }

        // Redirigir con un mensaje de error general
        return redirect()->back()->with('error', 'Error al subir la imagen a ImgBB o Instagram.');
    } else {
        // Redirigir si no se ha seleccionado una imagen
        return redirect()->back()->with('error', 'No se ha seleccionado una imagen.');
    }
}


    private function uploadImageAndGetUrl($imagePath)
    {
        $uploadUrl = 'https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY');

        try {
            $imageContent = base64_encode(file_get_contents($imagePath));
            Log::info('Encoded image content for ImgBB', ['content_length' => strlen($imageContent)]);

            $response = Http::asForm()->post($uploadUrl, [
                'image' => $imageContent
            ]);

            $responseData = $response->json();
            Log::info('ImgBB response', ['response' => $responseData]);

            if ($response->successful() && isset($responseData['data']['url'])) {
                return $responseData['data']['url'];
            } else {
                Log::error('ImgBB upload failed', ['status' => $response->status(), 'response' => $responseData]);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error uploading image to ImgBB', ['exception' => $e]);
            return false;
        }
    }

    private function postImageToInstagram($imageUrl)
    {
        try {
            // Paso 1: Crear el contenedor de medios
            $endpoint = "https://graph.facebook.com/v19.0/{$this->igUserId}/media";
            $params = [
                'image_url' => $imageUrl,
                'caption' => 'Aquí va tu descripción',
                'access_token' => $this->accessToken,
            ];

            $response = $this->httpClient->post($endpoint, ['form_params' => $params]);
            $responseData = json_decode($response->getBody(), true);
            Log::info('Instagram media container response', ['response' => $responseData]);

            if (!isset($responseData['id'])) {
                Log::error('Error creating Instagram media container', ['response' => $responseData]);
                return false;
            }

            $mediaContainerId = $responseData['id'];

            // Paso 2: Publicar el contenedor de medios
            $publishEndpoint = "https://graph.facebook.com/v19.0/{$this->igUserId}/media_publish";
            $publishParams = [
                'creation_id' => $mediaContainerId,
                'access_token' => $this->accessToken,
            ];

            $publishResponse = $this->httpClient->post($publishEndpoint, ['form_params' => $publishParams]);
            $publishResponseData = json_decode($publishResponse->getBody(), true);
            Log::info('Instagram publish response', ['response' => $publishResponseData]);

            if (!isset($publishResponseData['id'])) {
                Log::error('Error publishing Instagram media container', ['response' => $publishResponseData]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Instagram post exception', ['exception' => $e]);
            return false;
        }
    }
}
