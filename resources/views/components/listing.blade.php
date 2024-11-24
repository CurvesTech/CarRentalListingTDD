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

    <div class="mt-4 flex gap-2">
        <a class="border dark:border-gray-600 px-4 py-2 rounded-lg" href="{{ route('listings.edit', $listing) }}">
            {{ __('Edit') }}
        </a>
        <form action="{{ route('listings.destroy', $listing) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="border dark:border-gray-600 px-4 py-2 rounded-lg" type="submit">
                {{ __('Delete') }}
            </button>
        </form>
    </div>
</div>