<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public $incrementing = false;
    public $fillable = ['id', 'name', 'image'];
    public $responseFields = ['id', 'name', 'image'];
}
