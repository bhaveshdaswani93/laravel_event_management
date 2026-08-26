<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests can browse and view events', function () {
    $event = Event::factory()->create();

    $this->get(route('events.index'))->assertOk();
    $this->get(route('events.show', $event))->assertOk();
});

test('guests are redirected to login when managing events', function () {
    $event = Event::factory()->create();

    $this->get(route('events.create'))->assertRedirect(route('login'));
    $this->post(route('events.store'), [])->assertRedirect(route('login'));
    $this->get(route('events.edit', $event))->assertRedirect(route('login'));
    $this->put(route('events.update', $event), [])->assertRedirect(route('login'));
    $this->delete(route('events.destroy', $event))->assertRedirect(route('login'));
});

test('any authenticated user can create an event', function () {
    $response = $this->actingAs(User::factory()->create())
        ->post(route('events.store'), [
            'title' => 'New Event',
            'description' => null,
            'location' => null,
            'starts_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'capacity' => 50,
        ]);

    $response->assertRedirect(route('events.show', Event::sole()));
});

test('a user cannot edit another user\'s event', function () {
    $event = Event::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->get(route('events.edit', $event))
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->put(route('events.update', $event), ['title' => 'Hijacked'])
        ->assertForbidden();

    expect($event->fresh()->title)->not->toBe('Hijacked');
});

test('a user cannot delete another user\'s event', function () {
    $event = Event::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->delete(route('events.destroy', $event))
        ->assertForbidden();

    $this->assertModelExists($event);
});

test('the owner can edit and delete their own event', function () {
    $event = Event::factory()->create();

    $this->actingAs($event->user)
        ->get(route('events.edit', $event))
        ->assertOk();

    $this->actingAs($event->user)
        ->delete(route('events.destroy', $event))
        ->assertRedirect(route('events.index'));

    $this->assertModelMissing($event);
});

test('the create button is only shown to authenticated users', function () {
    $this->get(route('events.index'))->assertDontSee('Create Event');

    $this->actingAs(User::factory()->create())
        ->get(route('events.index'))
        ->assertSee('Create Event');
});

test('edit and delete controls are only shown to the event owner', function () {
    $event = Event::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->get(route('events.show', $event))
        ->assertDontSee('Delete Event');

    $this->actingAs($event->user)
        ->get(route('events.show', $event))
        ->assertSee('Delete Event');
});
