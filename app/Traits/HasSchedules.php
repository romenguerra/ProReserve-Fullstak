<?php

namespace App\Traits;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSchedules
{
    public function schedules(): MorphMany
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}
