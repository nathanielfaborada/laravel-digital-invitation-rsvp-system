<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'unique_code',
        'max_companions',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            if (empty($guest->unique_code)) {
                $guest->unique_code = Str::random(10);
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function rsvp()
    {
        return $this->hasOne(Rsvp::class);
    }
}