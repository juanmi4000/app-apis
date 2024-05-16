<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Http;

class SubirImagenController extends Controller
{
    public function subirImagen(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para asegurar que se recibe una imagen
        ]);

        // Subir la imagen a ImgBB
        $response = Http::attach(
            'image', 
            file_get_contents($request->file('imagen')->getRealPath()), 
            'imagen.jpg' // Puedes cambiar el nombre de la imagen aquí
        )->post('https://api.imgbb.com/1/upload?key=' . env('IMG_BB_API_KEY'));

        // Verificar si la carga de la imagen fue exitosa
        if ($response->successful()) {
            $imageUrl = $response->json()['data']['url'];
            // Aquí puedes hacer cualquier otra cosa con la URL de la imagen, como guardarla en una base de datos o mostrarla en la vista
            return "Imagen subida exitosamente. URL de la imagen: $imageUrl";
        } else {
            return "Error al subir la imagen a ImgBB.";
        }
    }

    public function obtenerImagenesInstagram()
    {
        // Configurar credenciales de la API de Facebook
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION'),
        ]);

        // Token de acceso de Instagram
        $accessToken = env('INSTAGRAM_ACCESS_TOKEN');
        // ID de usuario de Instagram
        $igUserId = env('INSTAGRAM_USER_ID');

        try {
            // Realizar una solicitud GET para obtener las imágenes de la cuenta de Instagram
            $response = $fb->get('/' . $igUserId . '/media', $accessToken);
            $graphEdge = $response->getGraphEdge();

            // Iterar sobre las imágenes y mostrarlas en la página
            $images = [];
            foreach ($graphEdge as $graphNode) {
                $images[] = $graphNode->getField('media_url');
            }
            // Puedes hacer cualquier otra cosa con las imágenes, como pasarlas a la vista para mostrarlas
            return $images;
        } catch (FacebookResponseException $e) {
            return 'Error al obtener las imágenes de Instagram: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            return 'Error de SDK de Facebook: ' . $e->getMessage();
        }
    }
}
