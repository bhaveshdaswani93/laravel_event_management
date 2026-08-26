<?php

namespace App\Http\Controllers;

use App\Actions\RegisterAttendeeForEvent;
use App\Http\Requests\RegisterAttendeeRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class EventAttendeeController extends Controller
{
    /**
     * Register an attendee for the given event.
     */
    public function store(RegisterAttendeeRequest $request, Event $event, RegisterAttendeeForEvent $registerAttendee): RedirectResponse
    {
        $registerAttendee->handle($event, $request->validated());

        return redirect()->route('events.show', $event)
            ->with('status', 'You are registered for this event.');
    }
}
