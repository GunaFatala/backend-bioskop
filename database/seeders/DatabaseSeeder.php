<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat User Dummy
        DB::table('users')->insert([
            'name' => 'Si Paling Nonton',
            'email' => 'user@test.com',
            'password' => Hash::make('password'), // passwordnya 'password'
        ]);

        // 2. Buat Studio
        $studio1 = DB::table('studios')->insertGetId([
            'name' => 'Studio 1 (Regular)',
            'total_rows' => 8, // Baris A - H
            'total_cols' => 10, // 10 Kursi per baris
        ]);

        $studio2 = DB::table('studios')->insertGetId([
            'name' => 'Studio 2 (VIP)',
            'total_rows' => 5,
            'total_cols' => 8,
        ]);

        // 3. Buat Film (Movies)
        $movie1 = DB::table('movies')->insertGetId([
            'title' => 'Flutter Man: The Widget War',
            'description' => 'Seorang programmer berjuang melawan bug di production sebelum deadline menyerang.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Flutter+Man', // Gambar dummy
            'duration_minutes' => 120,
        ]);

        $movie2 = DB::table('movies')->insertGetId([
            'title' => 'Laravel Chronicles',
            'description' => 'Drama romantis antara Model, View, dan Controller.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Laravel+Chronicles',
            'duration_minutes' => 95,
        ]);

        // 4. Buat Jadwal Tayang (Showtimes)
        // Jadwal Hari Ini & Besok
        DB::table('showtimes')->insert([
            [
                'movie_id' => $movie1,
                'studio_id' => $studio1,
                'start_time' => Carbon::now()->addHours(2), // 2 jam dari sekarang
                'price' => 50000,
            ],
            [
                'movie_id' => $movie1,
                'studio_id' => $studio1,
                'start_time' => Carbon::now()->addHours(5),
                'price' => 50000,
            ],
            [
                'movie_id' => $movie2,
                'studio_id' => $studio2, // Di studio VIP
                'start_time' => Carbon::tomorrow()->hour(19)->minute(0), // Besok jam 7 malam
                'price' => 75000,
            ],
        ]);
    }
}