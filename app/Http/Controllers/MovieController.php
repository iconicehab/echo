<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'popularity.desc'); // default sort
        $movies = [];
        $sortOptions = [
            'popularity.desc' => 'Most Popular',
            'vote_average.desc' => 'Top Rated',
            'release_date.desc' => 'Newest',
        ];
        $apiKey = env('TMDB_API_KEY');
        $page = max(1, (int)$request->input('page', 1));
        $totalPages = 1;
        if ($search) {
            $response = Http::get('https://api.themoviedb.org/3/search/movie', [
                'api_key' => $apiKey,
                'query' => $search,
                'language' => 'en-US',
                'include_adult' => false,
                'page' => $page,
            ]);
            $results = $response->json('results') ?? [];
            $totalPages = $response->json('total_pages') ?? 1;
            // Sort client-side if needed
            if ($sort === 'vote_average.desc') {
                usort($results, fn($a, $b) => ($b['vote_average'] <=> $a['vote_average']));
            } elseif ($sort === 'release_date.desc') {
                usort($results, fn($a, $b) => strcmp($b['release_date'] ?? '', $a['release_date'] ?? ''));
            } else { // popularity.desc
                usort($results, fn($a, $b) => ($b['popularity'] <=> $a['popularity']));
            }
            $movies = collect($results)->map(function ($movie) {
                return [
                    'Title' => $movie['title'] ?? '',
                    'Year' => isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '',
                    'Poster' => $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'https://via.placeholder.com/300x445?text=No+Image',
                    'id' => $movie['id'],
                ];
            })->toArray();
        } else {
            // Use discover endpoint for sorting
            $response = Http::get('https://api.themoviedb.org/3/discover/movie', [
                'api_key' => $apiKey,
                'language' => 'en-US',
                'sort_by' => $sort,
                'include_adult' => false,
                'page' => $page,
            ]);
            $results = $response->json('results') ?? [];
            $totalPages = $response->json('total_pages') ?? 1;
            $movies = collect($results)->map(function ($movie) {
                return [
                    'Title' => $movie['title'] ?? '',
                    'Year' => isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '',
                    'Poster' => $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'https://via.placeholder.com/300x445?text=No+Image',
                    'id' => $movie['id'],
                ];
            })->toArray();
        }
        return view('movies.index', [
            'movies' => $movies,
            'search' => $search,
            'sort' => $sort,
            'sortOptions' => $sortOptions,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function show($id)
    {
        $apiKey = env('TMDB_API_KEY');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => $apiKey,
            'language' => 'en-US',
        ]);
        $movie = $response->json();
        // Get full poster and backdrop URLs
        $movie['poster_full'] = $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : null;
        $movie['backdrop_full'] = $movie['backdrop_path'] ? 'https://image.tmdb.org/t/p/original' . $movie['backdrop_path'] : null;
        // Fetch cast (credits)
        $credits = Http::get("https://api.themoviedb.org/3/movie/{$id}/credits", [
            'api_key' => $apiKey,
            'language' => 'en-US',
        ])->json();
        $cast = collect($credits['cast'] ?? [])->take(8);
        // Fetch videos (trailers)
        $videos = Http::get("https://api.themoviedb.org/3/movie/{$id}/videos", [
            'api_key' => $apiKey,
            'language' => 'en-US',
        ])->json('results') ?? [];
        $trailer = collect($videos)->first(function ($video) {
            return $video['site'] === 'YouTube' && $video['type'] === 'Trailer';
        });
        // Fetch TMDb reviews
        $tmdbReviews = Http::get("https://api.themoviedb.org/3/movie/{$id}/reviews", [
            'api_key' => $apiKey,
            'language' => 'en-US',
        ])->json('results') ?? [];
        $tmdbReviews = collect($tmdbReviews)->take(5);
        // Fetch user-submitted reviews
        $userReviews = \App\Models\Review::with('user')->where('movie_id', $id)->orderBy('created_at', 'desc')->get();
        return view('movies.show', compact('movie', 'cast', 'trailer', 'tmdbReviews', 'userReviews'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        $results = [];
        if ($query) {
            $apiKey = env('TMDB_API_KEY');
            $response = Http::get('https://api.themoviedb.org/3/search/movie', [
                'api_key' => $apiKey,
                'query' => $query,
                'language' => 'en-US',
                'include_adult' => false,
            ]);
            $results = collect($response->json('results'))->take(7)->map(function ($movie) {
                return [
                    'id' => $movie['id'],
                    'title' => $movie['title'] ?? '',
                    'poster' => $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w92' . $movie['poster_path'] : null,
                ];
            })->values();
        }
        return response()->json($results);
    }
}
