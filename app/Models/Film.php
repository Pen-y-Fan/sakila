<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Film extends Model
{
    use HasFactory;

    /**
     * Protected fields when mass assigning
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'release_year' => 'integer',
    ];
    /**
     * Get the language associated with the Film.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the categories associated with the Film.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the Actors associated with the Film.
     */
    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class);
    }

    /**
     * Get the Inventories for the Film.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the Stores associated with the Film.
     */
    public function stores(): HasManyThrough
    {
        return $this->hasManyThrough(Store::class, Inventory::class, 'film_id', 'id', 'id', 'store_id');
    }

    /**
     * Get the Rentals associated with the Film.
     */
    public function rentals(): HasManyThrough
    {
        return $this->hasManyThrough(Rental::class, Inventory::class, 'film_id', 'inventory_id', 'id', 'id');
    }
}
