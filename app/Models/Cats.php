<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cats extends Model
{
    use HasFactory;

    protected $table = 'cats';
    protected $fillable = [ 'name', 'city', 'color' ];
}
