<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
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
     * Get the Film for the Inventory item (DVD).
     */
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }


    /**
     * Get the Store for the Inventory item (DVD).
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    // Rental
}
