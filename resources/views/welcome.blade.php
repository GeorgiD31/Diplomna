<x-app-layout>
    <div class="w-full px-4 py-8">

        <div class="category-links flex flex-wrap justify-center items-center gap-2 mb-4">
            @foreach(['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'] as $cat)
                <a href="/?category={{ $cat }}" class="text-blue-600 font-bold hover:text-blue-800">
                    {{ ucfirst($cat) }}
                </a>
            @endforeach
            @auth
                <a href="{{ route('home.preferred') }}" class="px-4 py-2 bg-green-500 text-black rounded hover:bg-green-700">
                    Preferred Categories
                </a>
            @endauth
        </div>

        <form method="GET" action="{{ route('home') }}" class="flex flex-wrap justify-center items-center gap-4 mb-8">
            <input type="text" name="search" placeholder="Search articles..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg">

            <select name="source" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg">
                <option value="">Select Source</option>
                @foreach($sources as $source)
                    <option value="{{ $source->id }}" {{ request('source') == $source->id ? 'selected' : '' }}>
                        {{ $source->name }}
                    </option>
                @endforeach
            </select>
        </form>

        @if ($articles->count())
            <div class="w-[55%] mx-auto flex flex-col gap-8">
                @foreach ($articles as $article)
                    <div class="border-b border-gray-200 pb-8">
                        <h2 class="text-xl font-semibold text-center">{{ $article->title }}</h2>
                        @if($article->url_to_image)
    <div class="my-4 flex justify-center">
        <img src="{{ $article->url_to_image }}" 
             alt="{{ $article->title }}" 
             style="width: 100%; max-width: 1000px; height: auto; object-fit: cover; border-radius: 0.5rem;">
    </div>
@endif

                        <p class="text-gray-600 text-center">{{ $article->description }}</p>
                        <div class="flex justify-center gap-4 mt-4">
                            @if($article->user_id)
                                <a href="{{ route('articles.show', $article->id) }}" class="text-blue-500 font-bold hover:underline">Read more</a>
                            @else
                                <a href="{{ $article->url }}" target="_blank" class="text-blue-500 font-bold hover:underline">Read more</a>
                            @endif
                            @auth
                                @if(Auth::user()->savedArticles->contains($article->id))
                                    <form action="{{ route('articles.unsave', $article->id) }}" method="POST" class="save-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 font-bold">Unsave</button>
                                    </form>
                                @else
                                    <form action="{{ route('articles.save', $article->id) }}" method="POST" class="save-form">
                                        @csrf
                                        <button type="submit" class="text-green-500 font-bold">Save</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">No articles available.</p>
        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $(document).on('submit', '.save-form', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var method = form.find('input[name="_method"]').val() || 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function(response) {
                        if (method === 'DELETE') {
                            form.find('button').text('Save').removeClass('text-red-500').addClass('text-green-500');
                            form.attr('action', url.replace('unsave', 'save'));
                            form.find('input[name="_method"]').remove();
                        } else {
                            form.find('button').text('Unsave').removeClass('text-green-500').addClass('text-red-500');
                            form.attr('action', url.replace('save', 'unsave'));
                            form.append('<input type="hidden" name="_method" value="DELETE">');
                        }
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/fetch-latest-news', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message); 
            })
            .catch(error => console.error('Error fetching news:', error));
        });
    </script>
</x-app-layout>
