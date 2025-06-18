<?php

namespace App\Modules\Car\Models;

use Database\Factories\CarFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $brand
 * @property string $model
 * @property int $year
 * @property string $color
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CarFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereYear($value)
 * @mixin \Eloquent
 */
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
