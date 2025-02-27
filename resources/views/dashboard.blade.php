<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900" id="articles-title">Your Articles</h3>
                        <div class="flex">
                            <a href="{{ route('articles.create') }}" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 mr-4">Create New Article +</a>
                            <button id="toggle-articles" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Saved Articles</button>
                        </div>
                    </div>
                    <div id="articles-container">
                        @foreach($myArticles as $article)
                            <div class="relative bg-white p-4 rounded-lg shadow-sm mb-4 cursor-pointer transition duration-150 ease-in-out hover:bg-gray-100 hover:shadow-lg">
                                <a href="{{ route('articles.show', $article->id) }}" class="flex items-center">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $article->title }}</h3>
                                        @if($article->url_to_image)
                                            <img src="{{ $article->url_to_image }}" alt="{{ $article->title }}" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                                        @endif
                                        <p class="text-gray-600">{{ $article->description }}</p>
                                    </div>
                                </a>
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(event, id) {
            event.stopPropagation();
            document.getElementById(id).classList.toggle('hidden');
        }

        document.getElementById('toggle-articles').addEventListener('click', function() {
            const button = this;
            const container = document.getElementById('articles-container');
            const title = document.getElementById('articles-title');
            const url = button.textContent === 'Saved Articles' ? '{{ route("dashboard.saved") }}' : '{{ route("dashboard.articles") }}';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = '';
                    data.articles.forEach(article => {
                        const articleDiv = document.createElement('div');
                        articleDiv.classList.add('relative', 'bg-white', 'p-4', 'rounded-lg', 'shadow-sm', 'mb-4', 'cursor-pointer', 'transition', 'duration-150', 'ease-in-out', 'hover:bg-gray-100', 'hover:shadow-lg');
                        articleDiv.innerHTML = `
                            <a href="/articles/${article.id}" class="flex items-center">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">${article.title}</h3>
                                    ${article.url_to_image ? `<img src="${article.url_to_image}" alt="${article.title}" style="max-width: 100%; height: auto; margin-bottom: 10px;">` : ''}
                                    <p class="text-gray-600">${article.description}</p>
                                </div>
                            </a>
                            <div class="relative z-50">
                                <button onclick="toggleDropdown(event, 'dropdown-${article.id}')" 
                                        class="p-2 text-gray-600 rounded-lg transition duration-150 ease-in-out transform hover:text-gray-800 hover:bg-gray-300 hover:scale-110 hover:rotate-12">
                                    &#x22EE;
                                </button>
                                <div id="dropdown-${article.id}" 
                                     class="hidden absolute right-0 mt-2 w-36 bg-white border rounded-lg shadow-lg z-[300]">
                                    ${button.textContent === 'Saved Articles' ? '' : `
                                    <a href="/articles/${article.id}/edit" 
                                       class="block px-4 py-2 text-sm text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 hover:pl-5">
                                        ✏️ Edit
                                    </a>`}
                                    <form action="/articles/${article.id}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 transition duration-150 ease-in-out hover:bg-gray-100 hover:pl-5">
                                            🗑️ Delete
                                        </button>   
                                    </form>
                                </div>
                            </div>
                        `;
                        container.appendChild(articleDiv);
                    });
                    if (button.textContent === 'Saved Articles') {
                        button.textContent = 'Your Articles';
                        title.textContent = 'Saved Articles';
                    } else {
                        button.textContent = 'Saved Articles';
                        title.textContent = 'Your Articles';
                    }
                });
        });
    </script>
</x-app-layout>
