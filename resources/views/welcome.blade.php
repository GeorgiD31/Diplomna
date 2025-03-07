<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #111827;
        }
        .page-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .category-links {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .category-links a {
            text-decoration: none;
            color: #2563eb;
            font-weight: bold;
            transition: color 0.3s;
        }
        .category-links a:hover {
            color: #1e40af;
        }
        .article {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 0;
        }
        .article h2 {
            font-size: 1.25rem;
            margin: 0;
        }
        .article p {
            margin: 5px 0;
            color: #6b7280;
        }
        .article a, .article button {
            text-decoration: none;
            color: #2563eb;
            font-weight: bold;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: color 0.3s;
        }
        .article a:hover, .article button:hover {
            color: #1e40af;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    @include('layouts.navigation')

    <div class="page-container">
        <div class="category-links">
            @foreach(['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'] as $cat)
                <a href="/?category={{ $cat }}">{{ ucfirst($cat) }}</a>
            @endforeach
            @auth
                <a href="{{ route('home.preferred') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
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

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                Search
            </button>
        </form>

        @if ($articles->count())
            @foreach ($articles as $article)
                <div class="article" id="article-{{ $article->id }}">
                    <h2>{{ $article->title }}</h2>
                    @if($article->url_to_image)
                        <img src="{{ $article->url_to_image }}" alt="{{ $article->title }}" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                    @endif
                    <p>{{ $article->description }}</p>
                    @if($article->user_id)
                        <a href="{{ route('articles.show', $article->id) }}">Read more</a>
                    @else
                        <a href="{{ $article->url }}" target="_blank">Read more</a>
                    @endif
                    @auth
                        @if(Auth::user()->savedArticles->contains($article->id))
                            <form action="{{ route('articles.unsave', $article->id) }}" method="POST" class="save-form" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Unsave</button>
                            </form>
                        @else
                            <form action="{{ route('articles.save', $article->id) }}" method="POST" class="save-form" style="display:inline;">
                                @csrf
                                <button type="submit">Save</button>
                            </form>
                        @endif
                    @endauth
                </div>
            @endforeach
        @else
            <p class="text-center">No articles available.</p>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            $('.save-form').on('submit', function(e) {
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
                            form.find('button').text('Save');
                            form.attr('action', url.replace('unsave', 'save'));
                            form.find('input[name="_method"]').remove();
                        } else {
                            form.find('button').text('Unsave');
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

</body>
</html>
