<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;

class InstagramController extends Controller
{
    private $fb;
    private $accessToken;
    private $igUserId;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => 'v19.0',
        ]);

        $this->accessToken = env('INSTAGRAM_ACCESS_TOKEN');
        $this->igUserId = env('INSTAGRAM_USER_ID');
    }

    public function index()
{
    // Verificar configuración
    if (!$this->accessToken || !$this->igUserId) {
        return response()->json(['error' => 'Instagram access token or user ID is not set.'], 500);
    }

    try {
        $response = $this->fb->get('/' . $this->igUserId . '/media', $this->accessToken);
        $graphEdge = $response->getGraphEdge();

        if (!$graphEdge || !$graphEdge->asArray()) {
            return response()->json(['error' => 'No se encontraron datos en la respuesta de la API de Facebook.'], 500);
        }

        $data = [];
        foreach ($graphEdge as $item) {
            $data[] = $item->asArray();
        }
        return view('instagram.index', ['data' => $data]);
    } catch (\Facebook\Exceptions\FacebookResponseException $e) {
        return response()->json(['error' => 'Graph returned an error: ' . $e->getMessage()], 500);
    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        return response()->json(['error' => 'Facebook SDK returned an error: ' . $e->getMessage()], 500);
    }
}


    public function uploadImage(Request $request)
    {
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->getPathname();
            $imageUrl = $this->uploadImageAndGetUrl($imagePath);

            if ($imageUrl) {
                try {
                    $response = $this->fb->post('/' . $this->igUserId . '/media', [
                        'image_url' => $imageUrl,
                        'caption' => $request->input('comentario')
                    ], $this->accessToken);

                    $graphNode = $response->getGraphNode();
                    dd($graphNode);

                    if (isset($graphNode['id'])) {
                        $containerId = $graphNode['id'];

                        $response = $this->fb->post('/' . $this->igUserId . '/media_publish', [
                            'creation_id' => $containerId
                        ], $this->accessToken);

                        return redirect()->route('instagram.index')->with('success', 'Imagen publicada exitosamente en Instagram.');
                    } else {
                        return response()->json(['error' => 'No se pudo obtener el ID del contenedor de la imagen.'], 500);
                    }
                } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                    return response()->json(['error' => 'Facebook returned an error: ' . $e->getMessage()], 500);
                } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                    return response()->json(['error' => 'Facebook SDK returned an error: ' . $e->getMessage()], 500);
                }
            } else {
                return response()->json(['error' => 'Error al subir la imagen a ImgBB.'], 500);
            }
        } else {
            return response()->json(['error' => 'No se ha seleccionado una imagen.'], 400);
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
