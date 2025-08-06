<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
      'name',
    ];

    public function maintenance_order(): BelongsTo
    {
        return $this->belongsTo(MaintenanceOrder::class);
    }
}
