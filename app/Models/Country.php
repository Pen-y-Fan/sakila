<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /**
     * Get the Cities for the Country.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
