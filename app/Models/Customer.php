<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
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
     * Get the Store for this Customer.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the Address for this Customer.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get the Rentals for the Customer.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}
