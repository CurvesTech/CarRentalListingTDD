<div class="dark:text-gray-200 dark:border-gray-700 border rounded p-4">
    <img src="{{ $listing->images[0]->path }}" alt="{{ $listing->title }}" class="w-full h-48 object-cover rounded">
    <h1 class="text-xl mt-4">
        {{ $listing->title }}
    </h1>
    <p class="mt-2">
        {{ $listing->maker->title }} {{ $listing->model->title }} ({{ $listing->year }})
    </p>
    <p class="mt-2 text-lg font-bold">
        $ {{ $listing->price_per_day }} / {{ __('day') }}
    </p>
    <p class="mt-2">
        {{ $listing->phone_number }}
    </p>

    {{ $slot }}
</div>