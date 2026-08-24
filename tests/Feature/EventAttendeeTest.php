<?php

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('an event can have many attendees', function () {
    $event = Event::factory()->create();
    $attendees = Attendee::factory()->count(3)->create();

    $event->attendees()->attach($attendees);

    expect($event->attendees)->toHaveCount(3);
    expect($event->attendees->pluck('id')->sort()->values())
        ->toEqual($attendees->pluck('id')->sort()->values());
});

test('an attendee can register for many events', function () {
    $attendee = Attendee::factory()->create();
    $events = Event::factory()->count(3)->create();

    $attendee->events()->attach($events);

    expect($attendee->events)->toHaveCount(3);
    expect($attendee->events->pluck('id')->sort()->values())
        ->toEqual($events->pluck('id')->sort()->values());
});

test('registering an attendee for an event records the registration on both sides', function () {
    $event = Event::factory()->create();
    $attendee = Attendee::factory()->create();

    $event->attendees()->attach($attendee);

    expect($event->attendees->contains($attendee))->toBeTrue();
    expect($attendee->fresh()->events->contains($event))->toBeTrue();
});

test('an attendee cannot register for the same event twice', function () {
    $event = Event::factory()->create();
    $attendee = Attendee::factory()->create();

    $event->attendees()->attach($attendee);

    expect(fn () => $event->attendees()->attach($attendee))
        ->toThrow(QueryException::class);
});

test('an attendee can unregister from an event', function () {
    $event = Event::factory()->create();
    $attendee = Attendee::factory()->create();

    $event->attendees()->attach($attendee);
    $event->attendees()->detach($attendee);

    expect($event->attendees()->count())->toBe(0);
    $this->assertModelExists($attendee);
});

test('deleting an event removes its attendee registrations', function () {
    $event = Event::factory()->create();
    $attendee = Attendee::factory()->create();

    $event->attendees()->attach($attendee);
    $event->delete();

    expect($attendee->fresh()->events)->toHaveCount(0);
    $this->assertModelExists($attendee);
});
