<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('v1/external-books', 'BookExternalController@index');
Route::resource('v1/books', 'BookController');
