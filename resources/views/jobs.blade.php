<x-layout>
<x-slot:heading>Jobs </x-slot:heading>

<h1>Hello about page</h1>

@foreach ($jobs as $job)
<li><strong>{{$job ['title']}} : </strong> pays {{$job['salary']}}</li>
@endforeach
</x-layout>