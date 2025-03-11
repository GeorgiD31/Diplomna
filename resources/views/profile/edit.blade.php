<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

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

                        <div class="mb-4">
                            <label for="categories" class="block text-gray-700 dark:text-gray-300">Preferred Categories</label>
                            <select name="categories[]" id="categories" multiple class="w-full p-2 border rounded">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $user->preferences['categories'] ?? [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="sources" class="block text-gray-700 dark:text-gray-300">Preferred Sources</label>
                            <select name="sources[]" id="sources" multiple class="w-full p-2 border rounded">
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}" {{ in_array($source->id, old('sources', $user->preferences['sources'] ?? [])) ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Choices('#categories', {
                removeItemButton: true,
                searchEnabled: true,
                placeholderValue: 'Select categories...',
                searchFloor: 1,
                shouldSort: false,
                allowHTML: true,
                itemSelectText: '',
            });

            // Multi Select for Sources
            new Choices('#sources', {
                removeItemButton: true,
                searchEnabled: true,
                placeholderValue: 'Select sources...',
                searchFloor: 1,
                shouldSort: false,
                allowHTML: true,
                itemSelectText: '',
            });
        });
    </script>

    <style>
        .choices {
            width: 100%;
            max-width: 500px;
        }
        .choices__inner {
            border-radius: 8px;
            padding: 10px;
        }
        .choices__list--dropdown {
            max-height: 350px;
            overflow-y: auto;
            border-radius: 8px;
            background: white;
        }
        .choices__list--multiple .choices__item {
            background-color: #3b82f6;
            color: white;
            border-radius: 4px;
            padding: 5px 10px;
            margin: 2px;
        }
    </style>
</x-app-layout>
