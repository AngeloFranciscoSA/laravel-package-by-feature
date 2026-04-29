<?php

namespace App\Modules\Car\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'color',
        'price',
        'image_url',
        'km',
        'fuel',
        'transmission',
        'city',
        'state',
        'views',
        'featured',
        'badge',
        'seller_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'views' => 'integer',
        'km' => 'integer',
        'year' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    protected static function newFactory()
    {
        return CarFactory::new();
    }
}