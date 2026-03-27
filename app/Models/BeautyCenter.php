<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BeautyCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'image',
        'description',
        'address',
        'city',
        'postal_code',
        'phone',
        'email',
        'website',
        'wheelchair_access',
        'opening_time',
        'closing_time',
        'online_booking',
        'rating_average',
        'rating_count',
        'interest_id'
    ];

    public function interest(): BelongsTo
    {
        return $this->belongsTo(Interest::class);
    }

    public function services(): MorphMany
    {
        return $this->morphMany(Service::class, 'serviceable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }
}
