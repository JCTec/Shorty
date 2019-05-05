<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\URL;

class ShortyPublicController extends Controller
{

    function create(Request $request) {

        if($request->has('url')){

            $url = $request->get('url');

            $newURL = $this->shortified($url);

            return response()->json(['Status' => 'URL generado exitosamente.', 'codigo' => $newURL->shorty, 'url' => route('url.search', ['url' => $newURL->shorty])],200);

        }else{

            return response()->json(['error' => 'URL no especificado.'],500);
        }

    }

    function bulk(Request $request) {

        if($request->has('urls_array')){

            $urls = $request->get('urls_array');

            $toReturn = [];

            foreach ($urls as $url){
                $obj = $this->shortified($url);

                $toReturn[$url]["Codigo"] = $obj->shorty;
                $toReturn[$url]["url"] = route('url.search', ['url' => $obj->shorty]);
            }


            return response()->json(['Status' => 'URLs generados exitosamente.', 'urls' => $toReturn],200);

        }else{

            return response()->json(['error' => 'URL no especificado.'],500);
        }

    }

    private function shortified($url){

        $assure = false;

        while(!$assure){
            $shorty = $this->uniqidReal();

            if(!URL::where('shorty', '=', $shorty)->exists()){
                $assure = true;
            }
        }

        $newURL = new URL();

        $newURL->url = $url;
        $newURL->shorty = $shorty;

        $newURL->saveOrFail();

        return $newURL;
    }


    private function uniqidReal($lenght = 6) {

        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));

        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));

        } else {
            throw new Exception("no cryptographically secure random function available");
        }

        return substr(bin2hex($bytes), 0, $lenght);
    }
}
