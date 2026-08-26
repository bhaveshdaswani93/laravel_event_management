<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\View\View;

#[Middleware('auth', except: ['index', 'show'])]
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::latest('starts_at')->paginate(15);

        return view('events.index', ['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Authorize('create', Event::class)]
    public function create(): View
    {
        return view('events.create', ['event' => new Event]);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Authorize('create', Event::class)]
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = $request->user()->events()->create($request->validated());

        return redirect()->route('events.show', $event)
            ->with('status', 'Event created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load('attendees');

        return view('events.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Authorize('update', 'event')]
    public function edit(Event $event): View
    {
        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', 'event')]
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        return redirect()->route('events.show', $event)
            ->with('status', 'Event updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authorize('delete', 'event')]
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('status', 'Event deleted.');
    }
}
