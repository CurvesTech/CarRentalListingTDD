<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-3 gap-8">
            @forelse($listings as $listing)
                <x-listing :listing="$listing" />
            @empty
                <p class="text-center col-span-3">
                    {{ __('You have no listings.') }}
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>