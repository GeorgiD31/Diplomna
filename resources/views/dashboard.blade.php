<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Your Articles</h3>
                    <a href="{{ route('articles.create') }}" 
                       class="inline-block px-4 py-2 text-black font-semibold rounded transition duration-150 ease-in-out transform hover:scale-105 hover:shadow-lg hover:bg-gray-100">
                        Create New Article +
                    </a>
                </div>

                @if($myArticles->isEmpty())
                    <p class="text-gray-600 text-lg">No articles created yet.</p>
                @else
                    @foreach($myArticles as $article)
                        <div class="relative bg-white p-4 rounded-lg shadow-sm mb-4 cursor-pointer transition duration-150 ease-in-out hover:bg-gray-100 hover:shadow-lg"
                             onclick="window.location='{{ route('articles.show', $article->id) }}'">
                            
                            <div class="flex items-center">
                                @if($article->url_to_image)
                                    <img src="{{ $article->url_to_image }}" alt="{{ $article->title }}" 
                                         class="w-20 h-20 object-cover rounded-lg shadow-md mr-6">
                                @endif

                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $article->title }}</h3>
                                    <p class="text-gray-600">{{ $article->description }}</p>
                                </div>

                                <div class="relative z-50">
                                    <button onclick="toggleDropdown(event, 'dropdown-{{ $article->id }}')" 
                                            class="p-2 text-gray-600 rounded-lg transition duration-150 ease-in-out transform hover:text-gray-800 hover:bg-gray-300 hover:scale-110 hover:rotate-12">
                                        &#x22EE;
                                    </button>
                                    <div id="dropdown-{{ $article->id }}" 
                                         class="hidden absolute right-0 mt-2 w-36 bg-white border rounded-lg shadow-lg z-[300]">
                                        <a href="{{ route('articles.edit', $article->id) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 hover:pl-5">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 transition duration-150 ease-in-out hover:bg-gray-100 hover:pl-5">
                                                🗑️ Delete
                                            </button>   
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(event, id) {
            event.stopPropagation();
            document.getElementById(id).classList.toggle("hidden");
        }
        window.onclick = function () {
            document.querySelectorAll("[id^='dropdown-']").forEach(el => el.classList.add("hidden"));
        };
    </script>
</x-app-layout>
