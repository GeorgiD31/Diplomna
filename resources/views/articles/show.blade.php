<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($article->url_to_image)
                    <img src="{{ $article->url_to_image }}" alt="{{ $article->title }}" class="w-full h-auto mb-4">
                @endif

                <p class="mb-4 text-gray-700">{{ $article->description }}</p>

                <div class="prose max-w-none">
                    {!! nl2br(e($article->content)) !!}
                </div>

                @if($biasResult)
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">Political Bias Classification:</h3>
                        <p>{{ $biasResult['label'] }} ({{ $biasResult['score'] * 100 }}%)</p>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="text-blue-500 hover:underline">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
