<x-app-layout>
    <div class="container">
        <h1>Create a New Article</h1>

        @if($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('articles.store') }}" method="POST">
            @csrf
            
            <div>
                <label for="title">Title:</label><br>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required>
            </div>
            <br>
            
            <div>
                <label>Author:</label><br>
                <span>{{ auth()->user()->name }}</span>
                <input type="hidden" name="author" value="{{ auth()->user()->name }}">
            </div>
            <br>
            
            <div>
                <label for="description">Description:</label><br>
                <textarea name="description" id="description" required>{{ old('description') }}</textarea>
            </div>
            <br>
            
            <div>
                <label for="content">Content:</label><br>
                <textarea name="content" id="content">{{ old('content') }}</textarea>
            </div>
            <br>
            
            <div>
                <label for="url">URL:</label><br>
                <input type="url" name="url" id="url" value="{{ old('url') }}">
            </div>
            <br>
            
            <div>
                <label for="url_to_image">Image URL:</label><br>
                <input type="url" name="url_to_image" id="url_to_image" value="{{ old('url_to_image') }}">
            </div>
            <br>
            
            <div>
                <label for="source_name">Source Name:</label><br>
                <input type="text" name="source_name" id="source_name" value="{{ old('source_name') }}">
            </div>
            <br>
            
            <div>
                <label for="published_at">Published At:</label><br>
                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}">
            </div>
            <br>
            
            <div>
                <button type="submit">Create Article</button>
            </div>
        </form>
    </div>
</x-app-layout>
