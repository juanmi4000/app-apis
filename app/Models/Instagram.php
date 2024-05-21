<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class Instagram extends Model
{
    protected $fb;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION'),
        ]);
    }

    public function getImages()
    {
        try {
            // Obtén el token de acceso a largo plazo
            $accessToken = env('FACEBOOK_LONG_LIVED_ACCESS_TOKEN');

            // Haz una solicitud a la API de Instagram para obtener las imágenes
            $response = $this->fb->get('/me/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink', $accessToken);

            // Decodifica la respuesta
            $decodedResponse = $response->getDecodedBody();

            // Extrae las imágenes de la respuesta
            $images = $decodedResponse['data'];

            return $images;
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function uploadImage($image)
    {
        try {
            // Obtén el token de acceso a largo plazo
            $accessToken = env('FACEBOOK_LONG_LIVED_ACCESS_TOKEN');

            // Haz una solicitud a la API de Instagram para subir la imagen
            $data = [
                'image' => $this->fb->fileToUpload($image),
                'caption' => 'Esta es una imagen de prueba',
            ];
            $response = $this->fb->post('/me/photos', $data, $accessToken);

            // Decodifica la respuesta
            $decodedResponse = $response->getDecodedBody();

            // Extrae el ID de la imagen de la respuesta
            $imageId = $decodedResponse['id'];

            return $imageId;
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
}