<?php

namespace Tests\Unit;

use App\Model\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookStoreTest extends TestCase
{

    // to refresh the DB before calling any of the functions
    use RefreshDatabase;

    // /api/v1/external-books - GET
    public function testSuccessForFetchingExternalBooks(){

        $this->json('GET', '/api/v1/external-books')
            ->assertStatus(200)
            ->assertJson([

                "status_code" => 200,
                "status" => "success",

            ]);

    }

    // /api/v1/external-books - GET
    public function testSuccessForFetchingExternalBooksWithNameQuery(){

        $name = "A Game of Thrones";

        $this->json('GET', '/api/v1/external-books?name=' . $name)
            ->assertStatus(200)
            ->assertJson([

                "status_code" => 200,
                "status" => "success",
                "data" => [
                    [

                        "name" => $name

                    ]
                ]

            ]);

    }


    // /api/v1/books - POST
    public function testSuccessStoringOfABook()
    {
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

        $this->json('POST', '/api/v1/books', $data, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([

                "status_code" => 201,
                "status" => "success",
                "data" => [
                    "book" => [

                        "name" => "Twilight"

                    ]
                ]
            ]);

    }

    // /api/v1/books - POST
    public function testFailureStoringOfABookInvalidData()
    {
        $data = [

            "country" => "Nigeria",
            "number_of_pages" => 250,
            "publisher" => "Bologun Tolani",
            "release_date" => "2019-05-12",
            "authors" => [
                "Micheal Eva"
            ]

        ];

        $this->json('POST', '/api/v1/books', $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([

                "status_code" => 422,
                "error" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "isbn" => [
                        "The isbn field is required."
                    ],
                ]
            ]);

    }

    // /api/v1/books - GET
    public function testSuccessForFetchingAllBooks()
    {

        $this->createABook();

        $response =  $this->json('GET', '/api/v1/books')
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "data" => [

                    [
                        "name" => "Twilight",
                        "isbn" => "484-848499484",
                        "country" => "Nigeria",
                        "number_of_pages" => 250,
                        "publisher" => "Bologun Tolani",
                        "release_date" => "2019-05-12",
                        "authors" => [
                            "Micheal Eva"
                        ]

                    ]

                ]

            ]);

        $data = $response->getData();

        $books = $data->data;

        $this->assertEquals(count($books), 1);

    }

    // /api/v1/books - GET
    public function testSuccessForFetchingAllBooksWIthQuery()
    {

        $this->createABook();

        $search_name = "Twilight";
        $search_country = "Nigeria";

        $response =  $this->json('GET', '/api/v1/books?name=' . $search_name ."&country=" .$search_country )
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "data" => [

                    [
                        "name" => "Twilight",
                        "isbn" => "484-848499484",
                        "country" => "Nigeria",
                        "number_of_pages" => 250,
                        "publisher" => "Bologun Tolani",
                        "release_date" => "2019-05-12",
                        "authors" => [
                            "Micheal Eva"
                        ]

                    ]

                ]

            ]);

        $data = $response->getData();

        $books = $data->data;

        $this->assertEquals(count($books), 1);

    }

    // /api/v1/books/:id - GET
    public function testSuccessForFetchingABook()
    {

        $this->createABook();

        $book = Book::first();

        $this->json('GET', '/api/v1/books/' . $book->id)
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "data" => [

                    "name" => "Twilight",
                    "isbn" => "484-848499484",
                    "country" => "Nigeria",
                    "number_of_pages" => 250,
                    "publisher" => "Bologun Tolani",
                    "release_date" => "2019-05-12",
                    "authors" => [
                        "Micheal Eva"
                    ]

                ]

            ]);


    }

    // /api/v1/books/:id - GET
    public function testFailureForFetchingABookInvalidID()
    {
        $id = 999;

        $message = "The book with ID " . $id . " doesnt exist";

        $this->json('GET', '/api/v1/books/' . $id)
            ->assertStatus(404)
            ->assertJson([
                "status_code" => 404,
                "error" => $message,
            ]);


    }

    // /api/v1/books/:id - PUT
    public function testSuccessUpdatingOfABook()
    {

        $this->createABook();

        $book = Book::first();

        $data = [

            "country" => "Congo",
            "number_of_pages" => 310,
            "publisher" => "Bologun Tosh",

        ];

        $this->json('PUT', '/api/v1/books/' . $book->id, $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([

                "status_code" => 200,
                "status" => "success",
                "message" => "The book " . $book->name. " was updated successfully",
                "data" => [

                    "country" => "Congo",
                    "number_of_pages" => 310,
                    "publisher" => "Bologun Tosh"

                ]
            ]);

    }

    // /api/v1/books/:id - DELETE
    public function testSuccessForDeletingABook()
    {

        $this->createABook();

        $book = Book::first();

        $this->json('DELETE', '/api/v1/books/' . $book->id)
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 204,
                "status" => "success",
                "message" => "The book " . $book->name . " was deleted successfully",
            ]);


    }

    // /api/v1/books/:id - DELETE
    public function testFailureForDeletingABookInvalidID()
    {
        $id = 999;

        $message = "The book with ID " . $id . " doesnt exist";

        $this->json('DELETE', '/api/v1/books/' . $id)
            ->assertStatus(404)
            ->assertJson([
                "status_code" => 404,
                "error" => $message,
            ]);


    }



    public function createABook()
    {

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

        $this->json('POST', '/api/v1/books', $data, ['Accept' => 'application/json']);

    }
}
