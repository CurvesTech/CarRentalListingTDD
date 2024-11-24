<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-3 gap-8">
            @forelse($listings as $listing)
                <x-listing :listing="$listing">
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
                </x-listing>
            @empty
                <p class="text-center col-span-3">
                    {{ __('You have no listings.') }}
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>