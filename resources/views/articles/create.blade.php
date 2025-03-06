<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a New Article
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('articles.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Author</label>
                        <span>{{ auth()->user()->name }}</span>
                        <input type="hidden" name="author" value="{{ auth()->user()->name }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Description</label>
                        <textarea name="description" class="w-full p-2 border rounded" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Content</label>
                        <textarea name="content" class="w-full p-2 border rounded" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">URL</label>
                        <input type="url" name="url" value="{{ old('url') }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Image URL</label>
                        <input type="url" name="url_to_image" value="{{ old('url_to_image') }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Source</label>
                        <select name="source_id" class="w-full p-2 border rounded">
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="toggleNewSource" class="p-2 bg-gray-200 rounded">
                            Add New Source
                        </button>
                        <div id="newSourceField" class="hidden mt-2">
                            <label for="new_source" class="block text-gray-700">New Source Name</label>
                            <input type="text" name="new_source" id="new_source" value="{{ old('new_source') }}" class="w-full p-2 border rounded">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Published At</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="toggleCategories" class="p-2 bg-gray-200 rounded">
                            Select Categories
                        </button>
                        <div id="categoriesList" class="hidden mt-2 border p-2 rounded bg-gray-100">
                            @foreach($categories as $category)
                                <div>
                                    <label>
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="toggleNewCategory" class="p-2 bg-gray-200 rounded">
                            Add New Category
                        </button>
                        <div id="newCategoryField" class="hidden mt-2">
                            <label for="new_category" class="block text-gray-700">New Category Name</label>
                            <input type="text" name="new_category" id="new_category" value="{{ old('new_category') }}" class="w-full p-2 border rounded">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-black rounded">Create Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleCategories').addEventListener('click', function() {
            const categoriesList = document.getElementById('categoriesList');
            categoriesList.classList.toggle('hidden');
        });

        document.getElementById('toggleNewCategory').addEventListener('click', function() {
            const newCategoryField = document.getElementById('newCategoryField');
            newCategoryField.classList.toggle('hidden');
        });

        document.getElementById('toggleNewSource').addEventListener('click', function() {
            const newSourceField = document.getElementById('newSourceField');
            newSourceField.classList.toggle('hidden');
        });
    </script>

    <style>
        .hidden {
            display: none;
        }
    </style>
</x-app-layout>
