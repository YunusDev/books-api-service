<?php

use App\Model\Author;
use App\Model\Book;
use Illuminate\Database\Seeder;

class BookSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [

            "name" => "Twilight",
            "isbn" => "484-848499484",
            "country" => "Nigeria",
            "number_of_pages" => 250,
            "publisher" => "Bologun Tolani",
            "release_date" => "2019-05-12",
            "authors" => [
                "Micheal Eva"
            ]

        ];

        $book = new Book;

        $book->name = $data['name'];
        $book->isbn = $data['isbn'];
        $book->country = $data['country'];
        $book->number_of_pages = $data['number_of_pages'];
        $book->publisher = $data['publisher'];
        $book->release_date = $data['release_date'];

        $book->save();


        foreach($data['authors'] as $author_name){

            $author = new Author;

            $author->name = $author_name;
            $author->book_id = $book->id;

            $author->save();

        }
    }
}
