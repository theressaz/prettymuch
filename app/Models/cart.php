<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_price'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function cart_items(): HasMany
    {
        return $this->hasMany(cart_item::class);
    }

    public function reset_price (): void
    {
        $this->total_price = 0;
    }
}
