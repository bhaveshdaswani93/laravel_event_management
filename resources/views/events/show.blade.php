@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $event->title }}</h1>
        <div class="flex items-center gap-4">
            <a href="{{ route('events.edit', $event) }}" class="text-sm text-gray-600 hover:underline">Edit</a>
            <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:underline">Back to Events</a>
        </div>
    </div>

    <div class="rounded-md border border-gray-200 bg-white p-6">
        @if ($event->description)
            <p class="mb-4 whitespace-pre-line text-gray-700">{{ $event->description }}</p>
        @endif

        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-gray-500">Location</dt>
                <dd class="mt-1">{{ $event->location ?? 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Starts At</dt>
                <dd class="mt-1">{{ $event->starts_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Capacity</dt>
                <dd class="mt-1">{{ $event->capacity }}</dd>
            </div>
        </dl>
    </div>

    <form action="{{ route('events.destroy', $event) }}" method="POST" class="mt-6" onsubmit="return confirm('Delete this event?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:underline">Delete Event</button>
    </form>

    <div class="mt-10">
        <h2 class="mb-4 text-lg font-semibold">Attendees ({{ $event->attendees->count() }})</h2>

        @if ($event->attendees->isEmpty())
            <p class="text-sm text-gray-600">No one has registered yet.</p>
        @else
            <ul class="divide-y divide-gray-200 rounded-md border border-gray-200 bg-white">
                @foreach ($event->attendees as $attendee)
                    <li class="px-4 py-2 text-sm">
                        <span class="font-medium">{{ $attendee->name }}</span>
                        <span class="text-gray-500">{{ $attendee->email }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-10">
        <h2 class="mb-4 text-lg font-semibold">Register for this Event</h2>

        <form action="{{ route('events.attendees.store', $event) }}" method="POST" class="rounded-md border border-gray-200 bg-white p-6">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm text-white hover:bg-black">
                Register
            </button>
        </form>
    </div>
@endsection
