@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-3xl">
        @if($movie['backdrop_full'])
            <div class="rounded-lg overflow-hidden mb-6">
                <img src="{{ $movie['backdrop_full'] }}" alt="Backdrop" class="w-full object-cover max-h-80">
            </div>
        @endif
        <div class="flex flex-col md:flex-row gap-8 bg-white border border-black rounded-lg p-8">
            @if($movie['poster_full'])
                <img src="{{ $movie['poster_full'] }}" alt="Poster" class="w-48 h-72 object-contain bg-white rounded shadow">
            @endif
            <div class="flex-1">
                <h1 class="text-3xl font-extrabold text-black mb-2">{{ $movie['title'] }}</h1>
                <div class="mb-2 text-black">
                    <span class="font-semibold">Release:</span> {{ $movie['release_date'] ?? 'N/A' }}
                    <span class="mx-2">|</span>
                    <span class="font-semibold">Runtime:</span> {{ $movie['runtime'] ? $movie['runtime'] . ' min' : 'N/A' }}
                </div>
                <div class="mb-2 text-black">
                    <span class="font-semibold">Genres:</span>
                    @if(!empty($movie['genres']))
                        {{ collect($movie['genres'])->pluck('name')->join(', ') }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="mb-4 text-black">
                    <span class="font-semibold">Rating:</span> {{ $movie['vote_average'] }}/10 ({{ $movie['vote_count'] }} votes)
                </div>
                <p class="text-black text-lg mb-4">{{ $movie['overview'] }}</p>
                @if(!empty($movie['homepage']))
                    <a href="{{ $movie['homepage'] }}" target="_blank" class="inline-block px-4 py-2 bg-black text-white rounded hover:bg-white hover:text-black border border-black transition">Official Site</a>
                @endif
            </div>
        </div>
        @if($trailer)
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-black mb-4">Trailer</h2>
            <div class="w-full max-w-2xl mx-auto aspect-w-16 aspect-h-9 rounded overflow-hidden border border-black bg-black" style="aspect-ratio: 16/9;">
                <iframe src="https://www.youtube.com/embed/{{ $trailer['key'] }}" frameborder="0" allowfullscreen class="w-full h-full"></iframe>
            </div>
        </div>
        @endif
        @if($cast && $cast->count())
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-black mb-4">Cast</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                @foreach($cast as $person)
                    <div class="flex flex-col items-center bg-white border border-black rounded-lg p-4">
                        <img src="{{ $person['profile_path'] ? 'https://image.tmdb.org/t/p/w185' . $person['profile_path'] : 'https://via.placeholder.com/120x180?text=No+Image' }}" alt="{{ $person['name'] }}" class="w-24 h-36 object-contain bg-white rounded mb-2">
                        <div class="text-black font-semibold text-center">{{ $person['name'] }}</div>
                        <div class="text-black text-sm text-center">as {{ $person['character'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        @if($tmdbReviews && $tmdbReviews->count())
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-black mb-4">TMDb Reviews</h2>
            <div class="space-y-6">
                @foreach($tmdbReviews as $review)
                    <div class="bg-white border border-black rounded-lg p-4">
                        <div class="text-black font-semibold mb-1">{{ $review['author'] }}</div>
                        <div class="text-black text-sm mb-2">{{ \Carbon\Carbon::parse($review['created_at'])->format('M d, Y') }}</div>
                        <div class="text-black">{{ $review['content'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-black mb-4">User Reviews</h2>
            @auth
            <form method="POST" action="{{ route('reviews.store') }}" class="mb-8 bg-white border border-black rounded-lg p-4">
                @csrf
                <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                <div class="mb-4">
                    <label for="rating" class="block text-black font-semibold mb-1">Rating (1-10)</label>
                    <input type="number" min="1" max="10" name="rating" id="rating" class="form-input border-black rounded w-24 text-black" required>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-black font-semibold mb-1">Comment</label>
                    <textarea name="comment" id="comment" rows="3" class="form-textarea border-black rounded w-full text-black" required></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-white hover:text-black border border-black transition">Submit Review</button>
                @if(session('success'))
                    <div class="mt-2 text-green-600">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mt-2 text-red-600">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </form>
            @else
            <div class="mb-8 text-black">Please <a href="{{ route('login') }}" class="underline">login</a> to add a review.</div>
            @endauth
            <div class="space-y-6">
                @forelse($userReviews as $review)
                    <div class="bg-white border border-black rounded-lg p-4">
                        <div class="flex items-center mb-1">
                            <div class="text-black font-semibold mr-2">{{ $review->user->name }}</div>
                            <div class="text-black text-sm">rated <span class="font-bold">{{ $review->rating }}/10</span></div>
                        </div>
                        <div class="text-black text-sm mb-2">{{ $review->created_at->format('M d, Y') }}</div>
                        <div class="text-black">{{ $review->comment }}</div>
                    </div>
                @empty
                    <div class="text-black">No user reviews yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 