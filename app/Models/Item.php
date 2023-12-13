<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    
    protected $attributes = [
        'image_location' => 'nil',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image_location',
        'category',
        'stock',
        'price',
        'timestamps'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function cart_items(): HasMany
    {
        return $this->hasMany(cart_item::class);
    }

    public function item_ratings(): HasMany
    {
        return $this->hasMany(Item_rating::class);
    }
}
