<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /**
     * Get the City associated with this Address.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the Customer for this address.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the Store for this address.
     */
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    /**
     * Get the Staff in this Address.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
