<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'movie_id', 
        'studio_id', 
        'start_time', 
        'price'
    ];

    // Relasi ke Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Relasi ke Studio
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}