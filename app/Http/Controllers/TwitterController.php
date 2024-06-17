<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Noweh\TwitterApi\Client;
use Illuminate\Support\Facades\Log;


class TwitterController extends Controller
{
    private $setting;
    private $cliente;
    public function __construct()
    {
        $this->setting = [
            'account_id' => env('TWITTER_ACOUNT_ID'),
            'access_token' => env('TWITTER_ACCESS_TOKEN'),
            'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_KEY_SECRET'),
            'bearer_token' => env('TWITTER_BEARER_TOKEN'),
        ];
        $this->cliente = new Client($this->setting);

    }

    public function crearTweet()
    {
        try {
            // $return = $this->cliente->tweet()->create()->performRequest(['text' => 'Holaaa desde Laravel ']);
            $file_data = base64_encode(file_get_contents('url'));
            $media_info = $this->cliente->uploadMedia()->upload($file_data);
            $return = $this->cliente->tweet()->create()
                ->performRequest([
                    'text' => 'Test Tweet... ',
                    "media" => [
                        "media_ids" => [
                            (string) $media_info["media_id"]
                        ]
                    ]
                ])
            ;
            return view('twitter', ['return' => $return]);
        } catch (\Exception $e) {
            Log::error('Error al subir el tweet', ['exception' => $e]);
        }
        /*$file_data = base64_encode(file_get_contents('url'));
         $media_info = $this->cliente->uploadMedia()->upload($file_data);
        $return = $this->cliente->tweet()->create()
            ->performRequest([
                'text' => 'Test Tweet... ',
                "media" => [
                    "media_ids" => [
                        (string) $media_info["media_id"]
                    ]
                ]
            ])
        ; */
    }
}
