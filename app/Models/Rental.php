<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Rental extends Model
{
    use HasFactory;

    /**
     * Protected fields when mass assigning
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Get the Customer associated with this Rental.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the Staff associated with this Rental.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the Inventory associated with this Rental.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Get the Payments for the Rental.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the Film associated with the Rental.
     */
    public function film(): HasOneThrough
    {
        return $this->hasOneThrough(Film::class, Inventory::class, 'id', 'id', 'inventory_id', 'film_id');
    }

    /**
     * Get the Store associated with the Rental.
     */
    public function store(): HasOneThrough
    {
        return $this->hasOneThrough(Store::class, Inventory::class, 'id', 'id', 'inventory_id', 'store_id');
    }
}
