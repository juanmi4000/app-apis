<?php

namespace App;

use GuzzleHttp\Client;

class Instagram {
    private $api_key;
    private $client;

    public function __construct($api_key) {
        $this->api_key = $api_key;
        $this->client = new Client([
            'base_uri' => 'https://api.instagram.com/v1/', // URL base de la API de Instagram
            'headers' => ['Authorization' => 'Bearer ' . $this->api_key] // Autenticación
        ]);
    }

    public function getPosts($user_id) {
        $response = $this->client->get('users/' . $user_id . '/media/recent'); // Endpoint para obtener publicaciones recientes
        return json_decode($response->getBody(), true);
    }

    public function uploadImage($image_path) {
        $response = $this->client->post('media/upload', [ // Endpoint para subir una imagen
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen($image_path, 'r')
                ]
            ]
        ]);
        return json_decode($response->getBody(), true);
    }
}