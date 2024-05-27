<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FacebookAds\Api;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; // Importar la fachada de Storage

class InstagramController extends Controller
{
    private $fb;
    private $accessToken;
    private $igUserId;

    public function __construct()
    {
        // Inicializar la instancia de la API de Facebook
        Api::init(
            env('FB_APP_ID'),
            env('FB_APP_SECRET'),
            env('INSTAGRAM_ACCESS_TOKEN')
        );

        // Obtener la instancia de la API
        $this->fb = Api::instance();

        // Obtener el ID de usuario de Instagram
        $this->igUserId = env('INSTAGRAM_USER_ID');

        // Obtener el token de acceso de Instagram (si es necesario)
        $this->accessToken = env('INSTAGRAM_ACCESS_TOKEN');
    }

    public function index()
    {
        // Verificar configuración
        if (!$this->fb || !$this->igUserId) {
            return response()->json(['error' => 'Instagram access token or user ID is not set.'], 500);
        }

        try {
            // Tu lógica para obtener campañas
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
            return response()->json(['error' => 'Facebook authorization error: ' . $e->getMessage()], 500);
        } catch (\FacebookAds\Http\Exception\RequestException $e) {
            return response()->json(['error' => 'Facebook request error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
        ]);

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('uploads'); // Almacenar la imagen en el directorio "uploads"
            $imageUrl = url(Storage::url($imagePath)); // Obtener la URL de la imagen almacenada correctamente
            
            // Lógica para publicar la imagen en Instagram
            // ...

            return redirect()->back()->with('success', 'La imagen se ha subido correctamente a ImgBB y a Instagram.');
        } else {
            return redirect()->back()->with('error', 'No se ha seleccionado una imagen.');
        }
    }

    private function uploadImageAndGetUrl($imagePath)
    {
        $uploadUrl = 'https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY');

        try {
            $response = Http::asForm()->post($uploadUrl, [
                'image' => base64_encode(file_get_contents($imagePath))
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['data']['url'])) {
                return $responseData['data']['url'];
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
