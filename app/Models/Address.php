<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
