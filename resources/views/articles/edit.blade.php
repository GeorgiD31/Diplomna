<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Article
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('articles.update', $article->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ $article->title }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Author</label>
                        <span>{{ auth()->user()->name }}</span>
                        <input type="hidden" name="author" value="{{ auth()->user()->name }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Description</label>
                        <textarea name="description" class="w-full p-2 border rounded" required>{{ $article->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Content</label>
                        <textarea name="content" class="w-full p-2 border rounded" required>{{ $article->content }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">URL</label>
                        <input type="url" name="url" value="{{ $article->url }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Image URL</label>
                        <input type="url" name="url_to_image" value="{{ $article->url_to_image }}" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="toggleSources" class="p-2 bg-gray-200 rounded">
                            Select Source
                        </button>
                        <div id="sourcesList" class="hidden mt-2">
                            <label class="block text-gray-700">Source</label>
                            <div class="control">
                                <select name="source_id" id="choices-single-source" class="form-control">
                                    @foreach($sources as $source)
                                        <option value="{{ $source->id }}" {{ $article->source_id == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="toggleCategories" class="p-2 bg-gray-200 rounded">
                            Select Categories
                        </button>
                        <div id="categoriesList" class="hidden mt-2 border p-2 rounded bg-gray-100">
                            <label for="categories" class="block text-gray-700">Categories</label>
                            <select name="categories[]" id="categories" multiple class="w-full p-2 border rounded">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $article->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                        <button type="button" id="toggleNewCategory" class="p-2 bg-gray-200 rounded">
                            Add New Category
                        </button>
                        <div id="newCategoryField" class="hidden mt-2">
                            <label for="new_category" class="block text-gray-700">New Category Name</label>
                            <input type="text" name="new_category" id="new_category" value="{{ old('new_category') }}" class="w-full p-2 border rounded">
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-black rounded">Save Changes</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleSources').addEventListener('click', function() {
            document.getElementById('sourcesList').classList.toggle('hidden');
        });

        document.getElementById('toggleCategories').addEventListener('click', function() {
            document.getElementById('categoriesList').classList.toggle('hidden');
        });

        document.getElementById('toggleNewSource').addEventListener('click', function() {
            document.getElementById('newSourceField').classList.toggle('hidden');
        });

        document.getElementById('toggleNewCategory').addEventListener('click', function() {
            document.getElementById('newCategoryField').classList.toggle('hidden');
        });

        document.addEventListener('DOMContentLoaded', function() {
            new Choices('#choices-single-source', {
                searchEnabled: true,
                shouldSort: false,
                allowHTML: true,
                itemSelectText: '',
            });

            new Choices('#categories', {
                removeItemButton: true,
                searchEnabled: true,
                placeholderValue: 'Select categories...',
                searchFloor: 1,
                shouldSort: false,
                allowHTML: true,
                itemSelectText: '',
            });
        });
    </script>

    <style>
        .hidden {
            display: none;
        }
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
