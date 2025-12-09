<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // 1. Cek Kursi yang sudah laku (Biar di Flutter warnanya merah)
    public function getBookedSeats($showtime_id) {
        $bookedSeats = Ticket::whereHas('booking', function($q) use ($showtime_id) {
            $q->where('showtime_id', $showtime_id)
              ->where('status', 'paid');
        })->pluck('seat_number'); // Hasilnya cuma array: ["A1", "B5"]

        return response()->json($bookedSeats);
    }

    // 2. Proses Booking Baru
    public function store(Request $request) {
        $request->validate([
            'showtime_id' => 'required',
            'seats' => 'required|array|min:1', // Wajib array ["A1", "A2"]
        ]);

        $showtime = Showtime::find($request->showtime_id);
        $totalPrice = $showtime->price * count($request->seats);

        // Pakai DB Transaction biar aman
        return DB::transaction(function () use ($request, $showtime, $totalPrice) {
            
            // Cek bentrok kursi di detik terakhir
            $exists = Ticket::whereHas('booking', function($q) use ($request) {
                $q->where('showtime_id', $request->showtime_id)
                  ->where('status', 'paid');
            })->whereIn('seat_number', $request->seats)->exists();

            if($exists) {
                return response()->json(['message' => 'Kursi sudah diambil orang lain!'], 409);
            }

            // Simpan Header Booking
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'showtime_id' => $request->showtime_id,
                'total_price' => $totalPrice,
                'booking_code' => 'B-' . time() . mt_rand(100,999),
                'status' => 'paid'
            ]);

            // Simpan Detail Tiket
            foreach ($request->seats as $seat) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_number' => $seat
                ]);
            }

            return response()->json(['message' => 'Booking Berhasil!', 'data' => $booking]);
        });
    }

    // 3. Riwayat Tiket Saya
    public function myBookings(Request $request) {
        $bookings = Booking::with(['showtime.movie', 'showtime.studio', 'tickets'])
                    ->where('user_id', $request->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($bookings);
    }
}