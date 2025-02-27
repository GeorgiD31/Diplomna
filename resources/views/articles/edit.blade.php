<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Article
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('articles.update', $article->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ $article->title }}" class="w-full p-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Description</label>
                        <textarea name="description" class="w-full p-2 border rounded">{{ $article->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Image URL</label>
                        <input type="text" name="url_to_image" value="{{ $article->url_to_image }}" class="w-full p-2 border rounded">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save Changes</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
