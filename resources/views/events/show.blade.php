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
@endsection
