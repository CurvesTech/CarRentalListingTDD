<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg grid grid-cols-2 gap-10">
                    <div x-data="createListing()" class="max-w-2xl">
                        <div>
                            <x-input-label for="title">
                                {{ __('Title') }}
                            </x-input-label>
                            <x-text-input id="title" name="title" type="text" class="w-full mt-1 block" value="{{ old('title') }}"/>
                            <x-input-error class="mt-2" :messages="$errors->get('title')"/>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="maker">
                                {{ __('Maker') }}
                            </x-input-label>
                            <x-select-input x-model="selectedMaker" id="maker" name="maker_id" class="mt-2 w-full">
                                @foreach($makers as $maker)
                                    <option value="{{ $maker->id }}">{{ $maker->title }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('maker')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="model">
                                Model
                            </x-input-label>
                            <x-select-input id="model" name="model_id" class="mt-2 w-full">
                                <template x-for="model in models.filter((m) => m.maker_id == selectedMaker)" :key="model.id">
                                    <option x-text="model.title" :value="model.id"></option>
                                </template>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('model')" />
                        </div>

                        {{-- year --}}
                        <div class="mt-4">
                            <x-input-label for="year">
                                {{ __('Year') }}
                            </x-input-label>
                            <x-select-input id="year" name="year" class="mt-2 w-full">
                                @for($i = date('Y'); $i >= 1960; $i--)
                                    <option 
                                        value="{{ $i }}" 
                                        @if(old('year') == $i) selected @endif
                                    >{{ $i }}</option>
                                @endfor
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('year')"/>
                        </div>
                        {{-- /year --}}

                        {{-- registration number --}}
                        <div class="mt-4">
                            <x-input-label for="registration_number">
                                {{ __('Registration Number') }}
                            </x-input-label>
                            <x-text-input id="registration_number" name="registration_number" class="w-full mt-2" value="{{ old('registration_number') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('registration_number')"/>
                        </div>
                        {{-- /registration number --}}

                        {{-- transmission --}}
                        <div class="mt-4">
                            <x-input-label for="transmission">
                                {{ __('Transmission') }}
                            </x-input-label>
                            <x-select-input id="transmission" name="transmission" class="mt-2 w-full">
                                <option 
                                    value="manual"
                                    @if(old('transmission') == 'manual') selected @endif
                                >{{ __('Manual') }}</option>
                                <option 
                                    value="automatic"
                                    @if(old('transmission') == 'automatic') selected @endif
                                >{{ __('Automatic') }}</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('transmission')"/>
                        </div>
                        {{-- /transmission --}}

                        {{-- price --}}
                        <div class="mt-4">
                            <x-input-label for="price">
                                {{ __('Price per day') }}
                            </x-input-label>
                            <x-text-input id="price" name="price_per_day" class="w-full mt-2" value="{{ old('price_per_day') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('price_per_day')" />
                        </div>
                        {{-- /price --}}

                        {{-- Phone number --}}
                        <div class="mt-4">
                            <x-input-label for="phone_number">
                                {{ __('Phone Number') }}
                            </x-input-label>
                            <x-text-input id="phone_number" name="phone_number" class="w-full mt-2" value="{{ old('phone_number') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone_number')"/>
                        </div>
                        {{-- /Phone number --}}

                        <div class="mt-4">
                            <x-primary-button>{{ __('Create') }}</x-primary-button>
                        </div>  
                    </div>
                    <div x-data="imagePreview()" class="max-w-2xl">
                        <div>
                            <x-input-label for="images">{{ __('Images') }}</x-input-label>
                            <x-file-input 
                                id="images" 
                                name="images[]" 
                                class="hidden" 
                                x-ref="images" 
                                @change="handleFiles($event)"
                                multiple
                            />
                            <x-secondary-button type="button" x-on:click="$refs.images.click()" class="mt-2">
                                {{ __('Select Images') }}
                            </x-secondary-button>

                            <div class="mt-4 flex gap-4">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <img :src="preview" class="w-24 h-24 object-cover rounded-lg shadow-sm" />
                                </template>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('images')"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function createListing() {
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
