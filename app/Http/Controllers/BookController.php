<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Model\Author;
use App\Model\Book;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //

    use ApiResponser;

    public function index(Request $request){

        $params = $request->query->all();

        isset($params['name']) ? $name = $params['name'] : $name = "";
        isset($params['country']) ? $country = $params['country'] : $country = "";
        isset($params['publisher']) ? $publisher = $params['publisher'] : $publisher = "";
        isset($params['release_date']) ? $release_date = $params['release_date'] : $release_date = "";

        $books = Book::
                        query()
                        ->where('name', 'LIKE', "%{$name}%")
                        ->where('country', 'LIKE', "%{$country}%")
                        ->where('publisher', 'LIKE', "%{$publisher}%")
                        ->where('release_date', 'LIKE', "%{$release_date}%")
                        ->get();

        $books_collection = BookResource::collection($books);

        return $this->showAll($books_collection);

    }

    public function store(Request $request){

        $this->validate($request, [

            'name' => 'required|string|min:1|max:191',
            'isbn' => 'required|string|min:1|max:191',
            'country' => 'required|string|min:1|max:191',
            'number_of_pages' => 'required|numeric|min:0',
            'publisher' => 'required|string|min:1|max:191',
            'release_date' => 'required|string|min:1|max:191',
            "authors"    => "required|array|min:1",
            "authors.*"  => "required|string|distinct|min:1",

        ]);

        $book = new Book;

        $book->name = $request->name;
        $book->isbn = $request->isbn;
        $book->country = $request->country;
        $book->number_of_pages = $request->number_of_pages;
        $book->publisher = $request->publisher;
        $book->release_date = $request->release_date;

        $book->save();


        foreach($request->authors as $author_name){

            $author = new Author;

            $author->name = $author_name;
            $author->book_id = $book->id;

            $author->save();

        }

        $book_res = new BookResource($book);

        return $this->showOne($book_res, 201);

    }

    public function update(Request $request, $id){

        $this->validate($request, [

            'name' => 'string|min:1|max:191',
            'isbn' => 'string|min:1|max:191',
            'country' => 'string|min:1|max:191',
            'number_of_pages' => 'numeric|min:0',
            'publisher' => 'string|min:1|max:191',
            'release_date' => 'string|min:1|max:191',
            "authors"    => "array",
            "authors.*"  => "string|distinct|min:1",

        ]);

        $book = Book::find($id);

        if(!$book){

            return $this->errorResponse("The book with ID " . $id . " doesnt exist", 404);

        }

        if(isset($request->name)){
            $book->name = $request->name;
        }
        if(isset($request->isbn)){
            $book->isbn = $request->isbn;
        }
        if(isset($request->country)){
            $book->country = $request->country;
        }
        if(isset($request->number_of_pages)){
            $book->number_of_pages = $request->number_of_pages;
        }
        if(isset($request->publisher)){
            $book->publisher = $request->publisher;
        }
        if(isset($request->release_date)){
            $book->release_date = $request->release_date;
        }

        $book->save();

        if(isset($request->authors)){

            $book->authorsDelete();

            foreach($request->authors as $author_name){

                $author = new Author;

                $author->name = $author_name;
                $author->book_id = $book->id;

                $author->save();

            }

        }


        $book_fresh = $book->fresh();

        $book_res = new BookResource($book_fresh);

        return $this->showOneUpdate( "The book ". $book_fresh->name ." was updated successfully", 200, $book_res);


    }

    public function show(Request $request, $id){

        $book = Book::find($id);

        if(!$book){

            return $this->errorResponse("The book with ID " . $id . " doesnt exist", 404);

        }

        $book_res = new BookResource($book);

        return $this->showOneTwo($book_res);

    }


    public function destroy(Request $request, $id){

        $book = Book::find($id);

        if(!$book){

            return $this->errorResponse("The book with ID " . $id . " doesnt exist", 404);

        }

        $message = "The book ". $book->name ." was deleted successfully";

        $book->delete();

        return $this->showOneDelete($message, 204);

    }


}
