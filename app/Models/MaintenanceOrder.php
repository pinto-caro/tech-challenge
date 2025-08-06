<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceOrder extends Model
{
    protected $fillable = [
        'title',
        'status',
        'priority',
        'rejection_reason',
        'asset_id',
        'user_id',
    ];

    protected $attributes = [
        'status' => 'Created', 
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
