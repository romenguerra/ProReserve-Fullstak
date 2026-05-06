<?php

namespace App\Traits;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasResources
{
    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }
}
