<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'schedulable_id',
        'schedulable_type',
        'day_of_week',
        'opening_time',
        'closing_time',
        'is_closed',
        'capacity',
    ];

    public function schedulable()
    {
        return $this->morphTo();
    }
}
