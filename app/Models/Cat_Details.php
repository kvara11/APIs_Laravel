<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat_Details extends Model
{
    use HasFactory;
    protected $table = 'cat_details';
    protected $fillable = [ 'cat_id', 'height', 'weight' ];

}
