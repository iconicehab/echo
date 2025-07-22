@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 bg-white">
    <h1 class="text-3xl font-bold text-black mb-6">Movie List</h1>
    <form method="GET" action="{{ route('movies.index') }}" class="mb-8 flex flex-col sm:flex-row gap-2 items-start sm:items-end" x-data="{
        query: $refs.searchInput ? $refs.searchInput.value : '',
        results: [],
        show: false,
        fetchResults() {
            if (this.query.length < 2) { this.results = []; this.show = false; return; }
            fetch('/api/tmdb-search?query=' + encodeURIComponent(this.query))
                .then(r => r.json())
                .then(data => { this.results = data; this.show = true; });
        },
        selectMovie(title) {
            this.query = title;
            this.show = false;
            $refs.searchInput.value = title;
            $el.closest('form').submit();
        }
    }" @click.away="show = false">
        <div class="relative w-full sm:w-64">
            <input type="text" name="search" x-ref="searchInput" x-model="query" @input.debounce.250ms="fetchResults" autocomplete="off" placeholder="Search movies..." class="form-input w-full rounded border-black text-black" />
            <div x-show="show && results.length" class="absolute left-0 right-0 z-20 bg-white border border-black rounded mt-1 shadow-lg" style="max-height: 22rem; overflow-y: auto;">
                <template x-for="movie in results" :key="movie.id">
                    <a :href="'/movies/' + movie.id" class="flex items-center px-3 py-2 hover:bg-black hover:text-white transition cursor-pointer">
                        <img :src="movie.poster || 'https://via.placeholder.com/60x90?text=No+Image'" alt="Poster" class="w-8 h-12 object-contain bg-white mr-3 rounded border border-black">
                        <span x-text="movie.title"></span>
                    </a>
                </template>
            </div>
        </div>
        <select name="sort" onchange="this.form.submit()" class="form-select bg-white border-black rounded text-black">
            @foreach($sortOptions as $value => $label)
                <option value="{{ $value }}" @if($sort === $value) selected @endif>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-white hover:text-black border border-black transition">Search</button>
    </form>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($movies as $movie)
            <a href="{{ route('movies.show', $movie['id']) }}" class="bg-white shadow rounded-lg overflow-hidden flex flex-col hover:shadow-lg transition border border-black">
                <img src="{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x445?text=No+Image' }}" class="w-full h-96 object-contain bg-white" alt="{{ $movie['Title'] }}">
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <h5 class="font-semibold text-lg text-black">{{ $movie['Title'] }}</h5>
                    <p class="text-black">{{ $movie['Year'] }}</p>
                </div>
            </a>
        @empty
            <p class="text-black">No movies found.</p>
        @endforelse
    </div>
    @if($totalPages > 1)
    <div class="flex justify-center mt-10 space-x-2">
        @if($page > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" class="px-4 py-2 bg-black text-white rounded hover:bg-white hover:text-black border border-black transition">Previous</a>
        @endif
        @for($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++)
            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="px-4 py-2 {{ $i == $page ? 'bg-black text-white' : 'bg-white text-black border border-black' }} rounded hover:bg-black hover:text-white transition">{{ $i }}</a>
        @endfor
        @if($page < $totalPages)
            <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" class="px-4 py-2 bg-black text-white rounded hover:bg-white hover:text-black border border-black transition">Next</a>
        @endif
    </div>
    @endif
</div>
@endsection 