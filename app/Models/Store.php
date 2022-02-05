<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
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
     * Get the Address associated with this Store.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get the Manager for this store.
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class, 'id', 'manager_staff_id');
    }

    /**
     * Get the Customers for this store.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
    /**
     * Get the Store's customers payments.
     */
    public function customerPayments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Customer::class);
    }

    /**
     * Get the Store's films.
     */
    public function films(): HasManyThrough
    {
        return $this->hasManyThrough(Film::class, Inventory::class, 'store_id', 'id', 'id', 'film_id');
    }


}
