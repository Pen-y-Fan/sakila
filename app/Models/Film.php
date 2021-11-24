<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the language associated with the Film.
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
