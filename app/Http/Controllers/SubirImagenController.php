<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Http;
use App\Models\Imagen;

class SubirImagenController extends Controller
{
    public function subirImagen(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'comentario' => 'nullable|string|max:255',
        ]);

        // Manejar la subida de la imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $ruta = $imagen->store('imagenes', 'public');

            // Guardar información en la base de datos
            Imagen::create([
                'ruta' => $ruta,
                'comentario' => $request->comentario,
            ]);

            return redirect()->back()->with('success', 'Imagen subida correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un problema al subir la imagen.');
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
