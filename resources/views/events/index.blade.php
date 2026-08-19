@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Events</h1>
        <a href="{{ route('events.create') }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm text-white hover:bg-black">
            Create Event
        </a>
    </div>

    @if ($events->isEmpty())
        <p class="text-gray-600">No events yet.</p>
    @else
        <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Starts At</th>
                        <th class="px-4 py-2">Capacity</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($events as $event)
                        <tr>
                            <td class="px-4 py-2">
                                <a href="{{ route('events.show', $event) }}" class="font-medium text-blue-600 hover:underline">
                                    {{ $event->title }}
                                </a>
                            </td>
                            <td class="px-4 py-2">{{ $event->starts_at->format('M j, Y g:i A') }}</td>
                            <td class="px-4 py-2">{{ $event->capacity }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('events.edit', $event) }}" class="text-gray-600 hover:underline">Edit</a>

                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-2 text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    @endif
@endsection
