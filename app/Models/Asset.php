<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
      'name',
    ];

    public function maintenance_order(): HasMany
    {
        return $this->hasMany(MaintenanceOrder::class);
    }
}
