<?php

namespace App\Modules\Car\Models;

use Database\Factories\CarFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $brand
 * @property string $model
 * @property int $year
 * @property string $color
 * @property string $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static CarFactory factory($count = null, $state = [])
 * @method static Builder<static>|Car newModelQuery()
 * @method static Builder<static>|Car newQuery()
 * @method static Builder<static>|Car query()
 * @method static Builder<static>|Car whereBrand($value)
 * @method static Builder<static>|Car whereColor($value)
 * @method static Builder<static>|Car whereCreatedAt($value)
 * @method static Builder<static>|Car whereId($value)
 * @method static Builder<static>|Car whereModel($value)
 * @method static Builder<static>|Car wherePrice($value)
 * @method static Builder<static>|Car whereUpdatedAt($value)
 * @method static Builder<static>|Car whereYear($value)
 * @mixin Eloquent
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
