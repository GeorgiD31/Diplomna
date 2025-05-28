<x-app-layout>
    <div class="w-full px-4 py-8">
        @if (session('status'))
            <div class="mb-4 text-center text-green-500">
                {{ session('status') }}
            </div>
        @endif

        <div id="message" class="mb-4 text-center text-green-500 hidden"></div>

        <div class="category-links flex flex-wrap justify-center items-center gap-2 mb-4">
            @foreach(['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'] as $cat)
                <a href="/?category={{ $cat }}" class="text-blue-600 font-bold hover:text-blue-800 {{ request('category') == $cat ? 'underline' : '' }}">
                    {{ ucfirst($cat) }}
                </a>
            @endforeach
            @auth
                <a href="{{ route('home.preferred') }}" class="px-4 py-2 bg-green-500 text-black rounded hover:bg-green-700">
                    Preferred Categories
                </a>
            @endauth
        </div>

        <form method="GET" action="{{ route('home') }}" class="flex justify-center items-center gap-4 mb-8 w-full max-w-[600px] mx-auto">
            <input type="text" name="search" placeholder="Search articles..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg w-[400px] h-[44px]">

            <select name="source" id="source-select" class="px-4 py-2 border rounded-lg w-[400px] h-[44px]">
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
                        <div class="flex flex-col items-center gap-2 mt-4">
                            @if($article->user_id)
                                <a href="{{ route('articles.show', $article->id) }}" class="text-blue-500 font-bold hover:underline">Read more</a>
                            @else
                                <a href="{{ $article->url }}" target="_blank" class="text-blue-500 font-bold hover:underline">Read more</a>
                            @endif
                            @auth
                                @if(Auth::user()->savedArticles->contains($article->id))
                                    <form action="{{ route('articles.unsave', $article->id) }}" method="POST" class="save-form flex flex-col items-center">
                                        @csrf
                                        @method('DELETE')
                                        <span class="message hidden" style="color: red;"></span>
                                        <button type="submit" class="text-red-500 font-bold">Unsave</button>
                                    </form>
                                @else
                                    <form action="{{ route('articles.save', $article->id) }}" method="POST" class="save-form flex flex-col items-center">
                                        @csrf
                                        <span class="message hidden" style="color: green;"></span>
                                        <button type="submit" class="text-green-500 font-bold">Save</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-center">
                {{ $articles->links() }}
            </div>
        @else
            <p class="text-center text-gray-500">No articles available.</p>
        @endif

    </div>

    <div id="login-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <button id="close-login-modal" class="absolute top-2 right-2 text-gray-600">&times;</button>
            @include('auth.login')
        </div>
    </div>

    <div id="register-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <button id="close-register-modal" class="absolute top-2 right-2 text-gray-600">&times;</button>
            @include('auth.register')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        .choices__inner {
            width: 400px !important; 
            height: 44px !important; 
            display: flex;
            align-items: center;
        }
    </style>

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
                            showMessage(form, 'Article unsaved successfully.', 'red');
                        } else {
                            form.find('button').text('Unsave').removeClass('text-green-500').addClass('text-red-500');
                            form.attr('action', url.replace('save', 'unsave'));
                            form.append('<input type="hidden" name="_method" value="DELETE">');
                            showMessage(form, 'Article saved successfully.', 'green');
                        }
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });

            const sourceSelect = new Choices('#source-select', {
                searchEnabled: true,
                shouldSort: false,
                allowHTML: true,
                itemSelectText: '',
            });

            document.getElementById('source-select').addEventListener('change', function() {
                this.form.submit();
            });

            $('#open-login-modal').on('click', function() {
                $('#login-modal').removeClass('hidden');
            });

            $('#close-login-modal').on('click', function() {
                $('#login-modal').addClass('hidden');
            });

            $('#open-register-modal').on('click', function() {
                $('#register-modal').removeClass('hidden');
            });

            $('#close-register-modal').on('click', function() {
                $('#register-modal').addClass('hidden');
            });

            $('a[href="{{ route('register') }}"]').on('click', function(e) {
                e.preventDefault();
                $('#register-modal').removeClass('hidden');
            });

            $(document).on('click', 'a[href="{{ route('login') }}"]', function(e) {
                e.preventDefault();
                $('#register-modal').addClass('hidden');
                $('#login-modal').removeClass('hidden');
            });

            function showMessage(form, message, color) {
                const messageSpan = form.find('.message');
                messageSpan.stop(true, true).text(message).removeClass('hidden').css('color', color).show();
                setTimeout(() => {
                    messageSpan.fadeOut(() => {
                        messageSpan.addClass('hidden').text('');
                    });
                }, 3000);
            }
        });
    </script>
</x-app-layout>
