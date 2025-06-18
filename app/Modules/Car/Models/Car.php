<?php

namespace App\Modules\Car\Models;

use Database\Factories\CarFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model {
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'color',
        'price',
    ];

    protected static function newFactory(): CarFactory
    {
        return CarFactory::new();
    }
}
