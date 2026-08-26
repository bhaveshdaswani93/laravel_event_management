<?php

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('event show page displays the registration form', function () {
    $event = Event::factory()->create();

    $response = $this->get(route('events.show', $event));

    $response->assertOk();
    $response->assertSee('Register for this Event');
});

test('a visitor can register for an event', function () {
    $event = Event::factory()->create();

    $response = $this->post(route('events.attendees.store', $event), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $attendee = Attendee::sole();

    $response->assertRedirect(route('events.show', $event));
    expect($attendee->name)->toBe('Jane Doe');
    expect($attendee->email)->toBe('jane@example.com');
    expect($event->attendees()->whereKey($attendee->id)->exists())->toBeTrue();
});

test('a registered attendee appears in the event attendee list', function () {
    $event = Event::factory()->create();

    $this->post(route('events.attendees.store', $event), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $response = $this->get(route('events.show', $event));

    $response->assertOk();
    $response->assertSee('Jane Doe');
    $response->assertSee('jane@example.com');
});

test('registration requires a name and email', function () {
    $event = Event::factory()->create();

    $response = $this->post(route('events.attendees.store', $event), []);

    $response->assertSessionHasErrors(['name', 'email']);
    expect(Attendee::count())->toBe(0);
});

test('registration requires a valid email', function () {
    $event = Event::factory()->create();

    $response = $this->post(route('events.attendees.store', $event), [
        'name' => 'Jane Doe',
        'email' => 'not-an-email',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('an attendee cannot register for the same event twice', function () {
    $event = Event::factory()->create();
    $attendee = Attendee::factory()->create(['email' => 'jane@example.com']);
    $event->attendees()->attach($attendee);

    $response = $this->post(route('events.attendees.store', $event), [
        'name' => $attendee->name,
        'email' => $attendee->email,
    ]);

    $response->assertSessionHasErrors(['email']);
    expect($event->attendees()->count())->toBe(1);
});

test('an existing attendee reuses their record when registering for another event', function () {
    $attendee = Attendee::factory()->create(['email' => 'jane@example.com']);
    $event = Event::factory()->create();

    $this->post(route('events.attendees.store', $event), [
        'name' => $attendee->name,
        'email' => $attendee->email,
    ]);

    expect(Attendee::count())->toBe(1);
    expect($event->attendees()->whereKey($attendee->id)->exists())->toBeTrue();
});

test('registration is rejected once the event is at capacity', function () {
    $event = Event::factory()->create(['capacity' => 1]);
    $event->attendees()->attach(Attendee::factory()->create());

    $response = $this->post(route('events.attendees.store', $event), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $response->assertSessionHasErrors(['email']);
    expect($event->attendees()->count())->toBe(1);
    expect(Attendee::where('email', 'jane@example.com')->exists())->toBeFalse();
});

test('registration succeeds when the event still has capacity remaining', function () {
    $event = Event::factory()->create(['capacity' => 2]);
    $event->attendees()->attach(Attendee::factory()->create());

    $response = $this->post(route('events.attendees.store', $event), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $response->assertRedirect(route('events.show', $event));
    $response->assertSessionDoesntHaveErrors();
    expect($event->attendees()->count())->toBe(2);
    expect($event->attendees()->where('email', 'jane@example.com')->exists())->toBeTrue();
});
