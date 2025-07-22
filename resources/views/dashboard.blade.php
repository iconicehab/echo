@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-2xl space-y-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10">
            <h1 class="text-4xl font-extrabold text-black mb-4 sm:mb-0 flex items-center"
                x-data="{
                    full: 'Welcome to Echo.',
                    display: '',
                    i: 0,
                    done: false,
                    type() {
                        this.display = '';
                        this.i = 0;
                        this.done = false;
                        let interval = setInterval(() => {
                            if (this.i < this.full.length) {
                                this.display += this.full[this.i++];
                            } else {
                                clearInterval(interval);
                                this.done = true;
                            }
                        }, 80);
                    }
                }"
                x-init="type()"
            >
                <span x-text="display"></span>
                <span class="ml-1 w-2 align-baseline"
                      :class="done ? 'animate-blink' : ''"
                      x-show="done || i < full.length"
                      x-transition
                >|</span>
            </h1>
            <a href="{{ route('movies.index') }}" class="inline-block px-6 py-3 bg-black text-white font-semibold rounded shadow hover:bg-white hover:text-black hover:border hover:border-black transition">Browse Movies</a>
        </div>
        @auth
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="bg-black border border-black rounded-lg p-8 flex flex-col items-center">
                <div class="text-5xl font-extrabold text-white">{{ $reviewCount }}</div>
                <div class="mt-2 text-white text-lg">Reviews Written</div>
            </div>
        </div>
        @endauth
        {{-- Removed guest login/register box --}}
        @auth
        <div class="bg-white border border-black rounded-lg p-8">
            <h2 class="text-2xl font-bold text-black mb-4">Your Recent Reviews</h2>
            <ul>
                @forelse($recentReviews as $review)
                    <li class="mb-6 pb-4 border-b border-black last:border-b-0 last:mb-0 last:pb-0">
                        <div class="font-semibold text-black text-lg">{{ $review->movie_title }}</div>
                        <div class="text-black">Rating: {{ $review->rating }}/5</div>
                        <div class="text-black italic mt-1">"{{ $review->comment }}"</div>
                    </li>
                @empty
                    <li class="text-black">You haven’t written any reviews yet.</li>
                @endforelse
            </ul>
        </div>
        @endauth
    </div>
</div>

<style>
@keyframes blink {
  0%, 49% { opacity: 1; }
  50%, 100% { opacity: 0; }
}
.animate-blink {
  animation: blink 1s step-end infinite;
}
</style>
@endsection
