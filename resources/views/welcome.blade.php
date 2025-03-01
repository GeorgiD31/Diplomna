<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

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
            .container {
                max-width: 900px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            .header a {
                text-decoration: none;
                color: #2563eb;
                padding: 8px 12px;
                border: 1px solid #2563eb;
                border-radius: 5px;
                transition: 0.3s;
            }
            .header a:hover {
                background-color: #2563eb;
                color: #fff;
            }
            .category-links {
                margin-bottom: 20px;
            }
            .category-links a {
                margin-right: 10px;
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
        <div class="container">
            <div class="header">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="category-links">
                @foreach(['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'] as $cat)
                    <a href="/?category={{ $cat }}">{{ ucfirst($cat) }}</a>
                @endforeach
                @auth
                    <a href="{{ route('home.preferred') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">Preferred Categories</a>
                @endauth
            </div>

            <div>
                <h1>Latest Articles</h1>

                <form method="GET" action="{{ route('home') }}">
                    <input type="text" name="search" placeholder="Search articles..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg mb-4">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Search</button>
                </form>

                @if ($articles->count())
                    @foreach ($articles as $article)
                        <div class="article" id="article-{{ $article->id }}">
                            <h2>{{ $article->title }}</h2>
                            @if($article->url_to_image)
                                <img src="{{ $article->url_to_image }}" alt="{{ $article->title }}" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                            @endif
                            <p>{{ $article->description }}</p>
                            <a href="{{ $article->url }}" target="_blank">Read more</a>
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
                    <p>No articles available.</p>
                @endif
            </div>
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
    </body>
</html>
