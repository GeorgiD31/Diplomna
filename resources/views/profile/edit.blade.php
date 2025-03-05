<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="categories" :value="__('Preferred Categories')" />
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($categories as $category)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->name }}" {{ in_array($category->name, old('categories', $user->preferences['categories'] ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <x-input-label for="sources" :value="__('Preferred Sources')" />
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($sources as $source)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sources[]" value="{{ $source->id }}" {{ in_array($source->id, old('sources', $user->preferences['sources'] ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $source->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
