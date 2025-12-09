<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 
        'description', 
        'poster_url', 
        'duration_minutes'
    ];
    
    // Relasi: Satu film punya banyak jadwal tayang
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}