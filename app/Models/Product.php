<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Add this line!
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // Now this will work

    protected $fillable = [
        'name',
        'buying_price',
        'selling_price',
        'stock'
    ]; 
}