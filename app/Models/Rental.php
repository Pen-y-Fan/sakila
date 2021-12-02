<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    use HasFactory;

    /**
     * Protected fields when mass assigning
     * @var string[] $guarded
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
}
