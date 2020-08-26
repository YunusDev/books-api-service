<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookExternalResource;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class BookExternalController extends Controller
{
    //

    use ApiResponser;

    public function index(Request $request){

        $params = $request->query->all();

        isset($params['name']) ? $name = $params['name'] : $name = "";

        $client = new Client();
        $res = $client->request('GET', 'https://www.anapioficeandfire.com/api/books',
            [
                'query' => [

                    'name' =>	$name,

                ]
            ]);

        $data =  $res->getBody();

        $data = json_decode($data, false);

        $books_collection = BookExternalResource::collection($data);

        return $this->showAll($books_collection);


    }
}
