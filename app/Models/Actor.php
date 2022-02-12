<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the Films associated with the Category.
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class);
    }
}
