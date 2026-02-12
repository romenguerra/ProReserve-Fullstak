<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = ['name', 'slug', 'icon'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
