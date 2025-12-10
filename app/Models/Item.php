<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'image',
        'group_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the group that owns the item.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
