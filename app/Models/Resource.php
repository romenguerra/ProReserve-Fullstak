<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'resourceable_id',
        'resourceable_type',
        'name',
        'capacity',
        'resource_type',
        'unit_count',
        'unit_capacity',
        'max_guests_per_booking',
    ];

    public function resourceable(): MorphTo
    {
        return $this->morphTo();
    }
}
