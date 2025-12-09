<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Ambil semua film
    public function index() {
        return response()->json(Movie::all());
    }

    // Ambil detail film beserta jadwal tayangnya
    public function show($id) {
        // 'showtimes.studio' artinya: ambil jadwal, sekalian ambil data studionya
        $movie = Movie::with(['showtimes.studio'])->find($id);
        
        if(!$movie) {
            return response()->json(['message' => 'Film tidak ditemukan'], 404);
        }

        return response()->json($movie);
    }
}