@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">Log in</h1>

    <form action="{{ route('login.store') }}" method="POST" class="max-w-sm rounded-md border border-gray-200 bg-white p-6">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                autofocus
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm text-white hover:bg-black">
            Log in
        </button>
    </form>
@endsection
