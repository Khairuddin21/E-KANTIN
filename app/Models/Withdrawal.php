<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = [
        'seller_id',
        'amount',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
