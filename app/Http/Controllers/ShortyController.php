<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvImportRequest;
use App\Http\Requests\DownloadRequest;
use App\Http\Requests\MakeURL;
use App\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ShortyController extends Controller
{

    function index(){

        $urls = URL::all();

        return view('maker')->with(['urls' => $urls]);
    }

    function download(DownloadRequest $request){

        $path = $request->path;

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        return response()->download($path, 'data_export.csv', $headers);
    }

    function array_to_csv_download(array $array) {

        if (count($array) == 0) {
            return null;
        }

        $filename = "data_export.csv";

        $temporaryDirectory = (new TemporaryDirectory())
                                        ->force()
                                        ->create();

        $path = $temporaryDirectory->path($filename);

        $df = fopen($path, 'w');

        foreach ($array as $row) {
            fputcsv($df, $row);
        }

        fclose($df);

        return $path;
    }

    function maker(MakeURL $request){

        $newURL = $this->shortified($request->url);

        return redirect()->back()->with('success', 'URL generado exitosamente. <a class="linker" href="'. route('url.search', ['url' => $newURL->shorty]) .'">Codigo: '.$newURL->shorty.'</a>');
    }

    function bulker(CsvImportRequest $request){

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $toSend = [];
        $toReturn = [];

        foreach ($data as $url){
            $obj = $this->shortified($url[0]);

            array_push($toReturn, $obj);
            array_push($toSend, [$obj->shorty, route('url.search', ['url' => $obj->shorty])]);
        }

        $path = $this->array_to_csv_download($toSend);

        return view('bulker')->with(['urls' => $toReturn, 'path' => $path]);
    }

    function search($url) {

        if($url){

            $urlObj = URL::where('shorty', '=', $url)->firstOrFail();

            return Redirect::to($urlObj->url);

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
