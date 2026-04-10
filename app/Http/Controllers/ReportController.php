<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\ServiceRequest::with(['user', 'releasedBy'])
            ->where('status', 'completed');

        // Filter Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('taker_nik', 'like', "%{$search}%");
            });
        }

        // Filter Pickup Method (YBS / Diwakilkan)
        if ($request->has('pickup_method') && $request->pickup_method != '') {
            if ($request->pickup_method == 'ybs') {
                $query->whereNull('taker_nik');
            } elseif ($request->pickup_method == 'diwakilkan') {
                $query->whereNotNull('taker_nik');
            }
        }

        // Filter Date Range (picked_up_at)
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('picked_up_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('picked_up_at', '<=', $request->end_date);
        }

        $reports = $query->orderBy('picked_up_at', 'desc')->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;
        $pickupMethod = $request->pickup_method;

        $fileName = 'Laporan_Pengambilan_KTP';
        if ($startDate && $endDate) {
            $fileName .= '_' . $startDate . '_to_' . $endDate;
        }
        $fileName .= '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\KtpReportsExport($startDate, $endDate, $search, $pickupMethod), $fileName);
    }
}
