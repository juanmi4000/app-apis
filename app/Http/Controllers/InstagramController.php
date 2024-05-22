<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
    try {
        $response = $this->fb->get('/' . $this->igUserId . '/media', $this->accessToken);
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
        // Cuando Graph devuelve un error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
        // Cuando la validación falla o hay otros problemas locales
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    $graphEdge = $response->getGraphEdge();

    // Imprimir la respuesta completa
    echo '<pre>';
    print_r($graphEdge->asArray());
    echo '</pre>';

    $images = [];
    foreach ($graphEdge as $graphNode) {
        $array = $graphNode->asArray();
        if ($array && array_key_exists('media_url', $array)) {
            $images[] = $graphNode->getField('media_url');
        } else {
            // Manejo de errores: la API de Instagram no devolvió 'media_url'
            error_log('Instagram API did not return media_url for node: ' . print_r($graphNode, true));
        }
    }

    return view('index', ['images' => $images]);
}

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->getPathname();

            $imageUrl = $this->uploadImageAndGetUrl($imagePath);

            if ($imageUrl) {
                $response = $this->fb->post('/' . $this->igUserId . '/media', [
                    'image_url' => $imageUrl,
                    'caption' => $request->input('comentario')
                ], $this->accessToken);
                $graphNode = $response->getGraphNode();
                $containerId = $graphNode['id'];

                $response = $this->fb->post('/' . $this->igUserId . '/media_publish', [
                    'creation_id' => $containerId
                ], $this->accessToken);

                return redirect()->route('instagram.index')->with('success', 'Imagen publicada exitosamente en Instagram.');
            } else {
                return 'Error al subir la imagen a ImgBB.';
            }
        } else {
            return 'Error: No se ha seleccionado una imagen.';
        }
    }

    private function uploadImageAndGetUrl($imagePath)
    {
        $uploadUrl = 'https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY');

        $response = Http::asForm()->post($uploadUrl, [
            'image' => base64_encode(file_get_contents($imagePath))
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['data']) && isset($responseData['data']['url'])) {
                return $responseData['data']['url'];
            } else {
                return false;
            }
        }

        return false;
    }
}