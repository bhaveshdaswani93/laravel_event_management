<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
    <input
        type="text"
        name="title"
        id="title"
        value="{{ old('title', $event->title) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
    >
    @error('title')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea
        name="description"
        id="description"
        rows="4"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
    >{{ old('description', $event->description) }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
    <input
        type="text"
        name="location"
        id="location"
        value="{{ old('location', $event->location) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
    >
    @error('location')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="starts_at" class="block text-sm font-medium text-gray-700">Starts At</label>
    <input
        type="datetime-local"
        name="starts_at"
        id="starts_at"
        value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
    >
    @error('starts_at')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
    <input
        type="number"
        min="1"
        name="capacity"
        id="capacity"
        value="{{ old('capacity', $event->capacity) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring"
    >
    @error('capacity')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
