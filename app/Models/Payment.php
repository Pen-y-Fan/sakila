<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
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
     * Get the Customer associated with this Payment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the Staff associated with this Payment.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the Rental associated with this Payment.
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function scopePaymentAfter(Builder $query, string $date): Builder
    {
        return $query->where($this->qualifyColumn('payment_date'), '>=', $date . ' 00:00:00');
    }

    public function scopePaymentBefore(Builder $query, string $date): Builder
    {
        return $query->where($this->qualifyColumn('payment_date'), '<=', $date . ' 23:59:59');
    }
}
