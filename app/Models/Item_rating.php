<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item_rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'rating',
        'review',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item():BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
