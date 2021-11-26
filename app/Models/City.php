<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];


    /**
     * Get the Country associated with the City.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
