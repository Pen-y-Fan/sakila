<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /**
     * Get the Films for the language.
     */
    public function films(): HasMany
    {
        return $this->hasMany(Film::class);
    }
}
