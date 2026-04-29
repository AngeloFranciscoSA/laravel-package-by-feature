<?php

namespace App\Modules\Car\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $fillable = [
        'name',
        'city',
        'state',
        'rating',
        'reviews',
        'since',
        'phone',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'reviews' => 'integer',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class, 'seller_id');
    }
}