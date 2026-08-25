<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAttendeeRequest;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class EventAttendeeController extends Controller
{
    /**
     * Register an attendee for the given event.
     */
    public function store(RegisterAttendeeRequest $request, Event $event): RedirectResponse
    {
        $attendee = Attendee::firstOrCreate(
            ['email' => $request->validated('email')],
            ['name' => $request->validated('name')]
        );

        $event->attendees()->attach($attendee);

        return redirect()->route('events.show', $event)
            ->with('status', 'You are registered for this event.');
    }
}
