@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">Edit Event</h1>

    <form action="{{ route('events.update', $event) }}" method="POST">
        @csrf
        @method('PUT')

        @include('events._form', ['event' => $event])

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm text-white hover:bg-black">
                Update Event
            </button>
            <a href="{{ route('events.show', $event) }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection
