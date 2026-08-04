<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\TravelPlan;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelPortalController extends Controller
{
    /**
     * Tampilkan Dashboard Portal Agen Travel.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Cari entity Travel yang dimiliki oleh akun user ini
        $travel = Travel::where('user_id', $user->id)->first();

        if (!$travel) {
            // Fallback jika belum di-link, ambil travel pertama atau tampilkan pesan
            $travel = Travel::first();
        }

        $bookings = TravelPlan::where('travel_id', $travel->id ?? 0)
            ->with(['user'])
            ->orderByDesc('created_at')
            ->get();

        return view('travel.dashboard', compact('travel', 'bookings'));
    }

    /**
     * Aksi Agen Travel: Klik "Perjalanan Dimulai".
     */
    public function startTrip(TravelPlan $travelPlan)
    {
        $travelPlan->load('travel', 'user');

        $travelPlan->update([
            'trip_status'     => 'in_progress',
            'trip_started_at' => now(),
            'status'          => 'Sedang Berjalan',
        ]);

        // Notifikasi ke Pemesan (User)
        UserNotification::sendNotification(
            $travelPlan->user_id,
            '🚩 Perjalanan Dimulai!',
            'Pihak Agen Travel "' . ($travelPlan->travel->nama_travel ?? 'Travel Partner') . '" telah secara resmi memulai tur perjalanan Anda hari ini. Selamat menikmati petualangan!',
            'info'
        );

        return back()->with('success', 'Perjalanan berhasil dimulai! Status pesanan telah diperbarui.');
    }

    /**
     * Aksi Agen Travel: Klik "Perjalanan Berakhir".
     */
    public function endTrip(TravelPlan $travelPlan)
    {
        $travelPlan->load('travel', 'user');

        $travelPlan->update([
            'trip_status'   => 'completed',
            'trip_ended_at' => now(),
            'status'        => 'Selesai',
        ]);

        // Notifikasi ke Pemesan (User)
        UserNotification::sendNotification(
            $travelPlan->user_id,
            '🏁 Tur Perjalanan Selesai!',
            'Agen Travel "' . ($travelPlan->travel->nama_travel ?? 'Travel Partner') . '" telah menyelesaikan tur perjalanan Anda. Terima kasih telah menjelajah bersama TripMate!',
            'success'
        );

        // Notifikasi ke Admin untuk Pencairan Dana
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if ($adminUser) {
            UserNotification::sendNotification(
                $adminUser->id,
                '💸 Siap Payout Dana Escrow',
                'Agen Travel "' . ($travelPlan->travel->nama_travel ?? 'Travel') . '" telah menyelesaikan tur untuk Rencana "' . $travelPlan->nama_perjalanan . '". Mohon Admin mentransfer dana escrow ke travel.',
                'warning'
            );
        }

        return back()->with('success', 'Perjalanan berhasil diakhiri! Menunggu pencairan dana dari Admin ke rekening Travel Anda.');
    }
}
