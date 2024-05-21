<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use App\Instagram;

class InstagramController extends Controller
{
    protected $instagram;

    public function __construct(Instagram $instagram)
    {
        $this->instagram = $instagram;
    }

    public function index()
    {
        try {
            $images = $this->instagram->getImages();
        } catch (FacebookResponseException $e) {
            // Manejar excepción
        } catch (FacebookSDKException $e) {
            // Manejar excepción
        }

        return view('instagram', ['images' => $images]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        try {
            $this->instagram->uploadImage($request->file('image'));
        } catch (FacebookResponseException $e) {
            // Manejar excepción
        } catch (FacebookSDKException $e) {
            // Manejar excepción
        }

        return redirect()->back();
    }
}