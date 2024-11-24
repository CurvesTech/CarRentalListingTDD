<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
                @include('listings.partials.form')

                <div class="mt-4">
                    <x-primary-button>{{ __('Create') }}</x-primary-button>
                </div>  
            </form>
        </div>
    </div>

    <script>
        function mountMakers() {
            return {
                selectedMaker: {{ $makers->first()->id }},
                models: @json($models)
            };
        }

        function imagePreview() {
            return {
                previews: [],
                handleFiles(event) {
                    this.previews = [];
                    const files = event.target.files;
                    for (let i = 0; i < files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        }
    </script>
</x-app-layout>
