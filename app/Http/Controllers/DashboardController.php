<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $reviewCount = 3; // Dummy data
        $recentReviews = [
            (object)[
                'movie_title' => 'Inception',
                'rating' => 5,
                'comment' => 'Amazing movie!'
            ],
            (object)[
                'movie_title' => 'The Matrix',
                'rating' => 4,
                'comment' => 'A classic.'
            ],
            (object)[
                'movie_title' => 'Interstellar',
                'rating' => 5,
                'comment' => 'Mind-blowing visuals.'
            ],
        ];
        return view('dashboard', compact('reviewCount', 'recentReviews'));
    }
} 