<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerWallet extends Model
{
    protected $fillable = [
        'seller_id',
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'integer',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
