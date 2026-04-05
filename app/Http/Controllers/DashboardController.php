<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Base Query
        $query = ServiceRequest::query();

        // Filter role dihapus, semua petugas bisa melihat data seperti admin

        $allServices = $query->get();

        // Statistik
        $stats = [
            'total' => $allServices->count(),
            'completed' => $allServices->where('status', 'completed')->count(),
            'pending' => $allServices->where('status', '!=', 'completed')->count(),
        ];


        // 5 KTP terakhir yang diambil
        $recentPickups = ServiceRequest::with(['releasedBy'])
            ->where('status', 'completed')
            ->orderBy('picked_up_at', 'desc')
            ->take(5)
            ->get();

        // Data untuk Grafik (Contoh: 7 hari terakhir)
        $chartDataCompleted = [];
        $chartDataPending = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->translatedFormat('d M');
            
            $chartDataCompleted[] = ServiceRequest::whereDate('submission_date', $date)
                                      ->where('status', 'completed')
                                      ->count();
                                      
            $chartDataPending[] = ServiceRequest::whereDate('submission_date', $date)
                                    ->where('status', '!=', 'completed')
                                    ->count();
        }

        return view('dashboard.index', compact('user', 'stats', 'chartLabels', 'chartDataCompleted', 'chartDataPending', 'recentPickups'));
    }
}
