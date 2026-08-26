<?php

namespace App\Actions;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterAttendeeForEvent
{
    /**
     * Register an attendee for the given event.
     *
     * Runs inside a locked transaction so concurrent registrations can't
     * both pass the duplicate/capacity checks and overbook the event.
     *
     * @param  array{name: string, email: string}  $data
     */
    public function handle(Event $event, array $data): Attendee
    {
        $attendee = Attendee::createOrFirst(
            ['email' => $data['email']],
            ['name' => $data['name']]
        );

        DB::transaction(function () use ($event, $attendee) {
            $lockedEvent = Event::whereKey($event->id)->lockForUpdate()->firstOrFail();

            if ($lockedEvent->attendees()->whereKey($attendee->id)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'This email is already registered for this event.',
                ]);
            }

            if ($lockedEvent->attendees()->count() >= $lockedEvent->capacity) {
                throw ValidationException::withMessages([
                    'email' => 'This event has reached capacity.',
                ]);
            }

            $lockedEvent->attendees()->attach($attendee);
        });

        return $attendee;
    }
}
