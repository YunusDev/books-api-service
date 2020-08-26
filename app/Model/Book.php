<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected  $guarded = [];

    public function authors(){

        return $this->hasMany(Author::class);

    }

    public function authorsDelete(){

        Author::where('book_id', $this->id)->delete();


    }
}
