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
        // 1. Reset Table (Biar bersih dan ID mulai dari 1 lagi)
        // Hati-hati: disable foreign key check dulu biar gak error
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tickets')->truncate();
        DB::table('bookings')->truncate();
        DB::table('showtimes')->truncate();
        DB::table('movies')->truncate();
        DB::table('studios')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Buat User Admin
        DB::table('users')->insert([
            'name' => 'Guna (Admin)',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Buat 3 Studio (Regular, IMAX, Premiere)
        $studios = [
            ['id' => 1, 'name' => 'Studio 1 (Regular)', 'total_rows' => 8, 'total_cols' => 10], // Murah
            ['id' => 2, 'name' => 'IMAX Theater', 'total_rows' => 12, 'total_cols' => 16],      // Mahal
            ['id' => 3, 'name' => 'The Premiere', 'total_rows' => 5, 'total_cols' => 6],        // Sultan
        ];

        foreach ($studios as $studio) {
            DB::table('studios')->insert($studio);
        }

        // 4. Daftar Film
        $moviesData = [
            [
                'title' => 'Avatar: The Way of Water',
                'description' => 'Jake Sully tinggal bersama keluarga barunya di planet Pandora. Setelah ancaman kembali datang, Jake harus bekerja sama dengan Neytiri dan tentara ras Na\'vi.',
                'duration_minutes' => 192,
                'poster_url' => 'https://image.tmdb.org/t/p/original/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg',
            ],
            [
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Identitas Spider-Man terungkap, membuat tanggung jawabnya sebagai super hero berbenturan dengan kehidupan normalnya.',
                'duration_minutes' => 148,
                'poster_url' => 'https://image.tmdb.org/t/p/original/1g0dhYtq4irTY1GPXvft6k4GY0d.jpg',
            ],
            [
                'title' => 'The Conjuring: The Devil Made Me Do It',
                'description' => 'Pasangan Warren kembali menghadapi kasus supranatural yang paling mengerikan sepanjang karier mereka.',
                'duration_minutes' => 112,
                'poster_url' => 'https://image.tmdb.org/t/p/original/xbSuFiJbbBWCkyCCKIMfuDCA4yV.jpg',
            ],
            [
                'title' => 'Minions: The Rise of Gru',
                'description' => 'Berlatar tahun 1970-an, Gru muda mencoba bergabung dengan kelompok penjahat super bernama Vicious 6.',
                'duration_minutes' => 87,
                'poster_url' => 'https://image.tmdb.org/t/p/original/wKiOkZTN9lUUUNZLmtnwubZYONg.jpg',
            ],
            [
                'title' => 'Oppenheimer',
                'description' => 'Kisah ilmuwan Amerika J. Robert Oppenheimer dan perannya dalam pengembangan bom atom.',
                'duration_minutes' => 180,
                'poster_url' => 'https://image.tmdb.org/t/p/original/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg',
            ],
            [
                'title' => 'Barbie',
                'description' => 'Barbie mengalami krisis eksistensi dan memutuskan untuk meninggalkan Barbie Land demi mencari jati diri di dunia nyata.',
                'duration_minutes' => 114,
                'poster_url' => 'https://image.tmdb.org/t/p/original/iuFNMS8U5cb6xfzi51Dbkovj7vM.jpg',
            ],
        ];

        // Masukkan Film dan Simpan ID-nya
        $movieIds = [];
        foreach ($moviesData as $movie) {
            $movieIds[] = DB::table('movies')->insertGetId(array_merge($movie, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 5. GENERATE JADWAL PINTAR (ANTI BENTROK) 🧠
        
        // A. Kita buat dulu "Master List" semua kemungkinan slot yang ada
        // (Contoh: Hari Ini - Studio 1 - Jam 10, Hari Ini - Studio 1 - Jam 13, dst...)
        $dates = [Carbon::now(), Carbon::tomorrow()];
        $timeSlots = ['10:00', '13:00', '16:00', '19:00', '21:30'];
        
        $availableSlots = [];

        foreach ($dates as $date) {
            foreach ($studios as $studio) {
                foreach ($timeSlots as $time) {
                    $availableSlots[] = [
                        'date' => $date,
                        'studio_id' => $studio['id'],
                        'time' => $time,
                    ];
                }
            }
        }

        // B. Acak Slotnya biar kayak kocokan arisan
        shuffle($availableSlots);

        // C. Bagikan slot ke film-film
        // Setiap film minimal dapat 3-4 jadwal tayang acak
        $slotIndex = 0;
        $totalSlots = count($availableSlots);

        // Kita looping terus sampai slot habis atau secukupnya
        while ($slotIndex < $totalSlots) {
            // Ambil satu film secara bergantian (Round Robin)
            foreach ($movieIds as $movieId) {
                if ($slotIndex >= $totalSlots) break; // Kalau slot habis, berhenti

                // Ambil satu kartu slot dari tumpukan
                $slot = $availableSlots[$slotIndex];
                
                // Tentukan Harga berdasarkan Studio
                $price = match ($slot['studio_id']) {
                    1 => 40000,  // Regular
                    2 => 65000,  // IMAX
                    3 => 100000, // Premiere
                    default => 50000,
                };

                // Insert Jadwal
                DB::table('showtimes')->insert([
                    'movie_id' => $movieId,
                    'studio_id' => $slot['studio_id'],
                    'start_time' => $slot['date']->format('Y-m-d') . ' ' . $slot['time'] . ':00',
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $slotIndex++; // Pindah ke kartu slot berikutnya
            }
        }
    }
}