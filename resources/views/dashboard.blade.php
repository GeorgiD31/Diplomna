<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                <div class="mt-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold">Your Articles</h3>
                    <a href="{{ route('articles.create') }}" class="inline-block px-4 py-2 text-black font-semibold rounded transition duration-150 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-gray-100">
                        Create New Article +
                    </a>
                </div>
                
                <div class="mt-8">
                    @if($myArticles->isEmpty())
                        <p>No articles created yet.</p>
                    @else
                        @foreach($myArticles as $article)
                            <div class="flex items-center border-b border-gray-200 py-4">
                                @if($article->url_to_image)
                                    <div class="flex-shrink-0">
                                        <img src="{{ $article->url_to_image }}" alt="{{ $article->title }}" class="w-20 h-20 object-cover">
                                    </div>
                                @endif
                                <div class="ml-8 flex-1">
                                    <h3 class="text-lg font-semibold">{{ $article->title }}</h3>
                                    <p class="text-gray-600">{{ $article->description }}</p>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('articles.show', $article->id) }}" class="px-3 py-1 text-black rounded transition duration-150 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-gray-100">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
