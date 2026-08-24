<?php

namespace App\Models;

use Database\Factories\AttendeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email'])]
class Attendee extends Model
{
    /** @use HasFactory<AttendeeFactory> */
    use HasFactory;

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }
}
