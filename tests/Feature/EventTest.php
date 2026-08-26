<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('index displays events', function () {
    $event = Event::factory()->create();

    $response = $this->get(route('events.index'));

    $response->assertOk();
    $response->assertSee($event->title);
});

test('create displays the form', function () {
    $response = $this->actingAs(User::factory()->create())
        ->get(route('events.create'));

    $response->assertOk();
    $response->assertSee('Create Event');
});

test('store creates an event owned by the authenticated user', function () {
    $user = User::factory()->create();

    $data = [
        'title' => 'Laravel Conference',
        'description' => 'A conference about Laravel.',
        'location' => 'Austin, TX',
        'starts_at' => now()->addMonth()->format('Y-m-d H:i:s'),
        'capacity' => 100,
    ];

    $response = $this->actingAs($user)->post(route('events.store'), $data);

    $event = Event::sole();

    $response->assertRedirect(route('events.show', $event));
    expect($event->title)->toBe('Laravel Conference');
    expect($event->capacity)->toBe(100);
    expect($event->user_id)->toBe($user->id);
});

test('store requires the required fields', function () {
    $response = $this->actingAs(User::factory()->create())
        ->post(route('events.store'), []);

    $response->assertSessionHasErrors(['title', 'starts_at', 'capacity']);
    expect(Event::count())->toBe(0);
});

test('show displays the event', function () {
    $event = Event::factory()->create();

    $response = $this->get(route('events.show', $event));

    $response->assertOk();
    $response->assertSee($event->title);
});

test('edit displays the form with existing data', function () {
    $event = Event::factory()->create();

    $response = $this->actingAs($event->user)
        ->get(route('events.edit', $event));

    $response->assertOk();
    $response->assertSee($event->title);
});

test('update modifies an event', function () {
    $event = Event::factory()->create(['title' => 'Old Title']);

    $data = [
        'title' => 'New Title',
        'description' => $event->description,
        'location' => $event->location,
        'starts_at' => $event->starts_at->format('Y-m-d H:i:s'),
        'capacity' => $event->capacity,
    ];

    $response = $this->actingAs($event->user)
        ->put(route('events.update', $event), $data);

    $response->assertRedirect(route('events.show', $event));
    expect($event->fresh()->title)->toBe('New Title');
});

test('update requires the required fields', function () {
    $event = Event::factory()->create();

    $response = $this->actingAs($event->user)
        ->put(route('events.update', $event), []);

    $response->assertSessionHasErrors(['title', 'starts_at', 'capacity']);
});

test('destroy deletes the event', function () {
    $event = Event::factory()->create();

    $response = $this->actingAs($event->user)
        ->delete(route('events.destroy', $event));

    $response->assertRedirect(route('events.index'));
    $this->assertModelMissing($event);
});
