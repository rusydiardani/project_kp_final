<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['user', 'releasedBy']);

        // Filter Role Petugas (REMOVED: Now everyone can see all data)
        // if (auth()->user()->role === 'petugas') {
        //     $query->where('user_id', auth()->id());
        // }

        // Filter Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter Date Range (submission_date)
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('submission_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('submission_date', '<=', $request->end_date);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('services.index', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:service_requests,nik',
            'applicant_name' => 'required|string|max:255',
            'submission_date' => 'required|date',
        ], [
            'nik.unique' => 'Data NIK ini sudah ada di dalam antrian/sistem. NIK tidak boleh sama.',
        ]);

        ServiceRequest::create([
            'nik' => $validated['nik'],
            'user_id' => Auth::id(),
            'applicant_name' => $validated['applicant_name'],
            'submission_date' => $validated['submission_date'],
            'status' => 'pending',
        ]);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil didaftarkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceRequest $service)
    {
        // Petugas restriction removed: any auth user can update
        // if (auth()->user()->role === 'petugas' && $service->user_id !== auth()->id()) {
        //     abort(403);
        // }

        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:service_requests,nik,' . $service->id,
            'applicant_name' => 'required|string|max:255',
        ], [
            'nik.unique' => 'Data NIK ini sudah ada di dalam antrian/sistem. NIK tidak boleh sama.',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Data layanan diperbarui.');
    }

    public function destroy(ServiceRequest $service)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menghapus data.');
        }
        $service->delete();
        return redirect()->back()->with('success', 'Layanan dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menghapus data.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:service_requests,id'
        ]);

        ServiceRequest::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' layanan berhasil dihapus.');
    }

    public function markAsPickedUp(Request $request, ServiceRequest $service)
    {
        $rules = [
            'taker_phone' => 'required|string|max:15',
            'is_representative' => 'nullable|boolean',
        ];

        if ($request->has('is_representative') && $request->is_representative) {
            $rules['taker_nik'] = 'required|string|size:16';
        }

        $request->validate($rules);

        $service->update([
            'status' => 'completed',
            'taker_phone' => $request->taker_phone,
            'taker_nik' => $request->has('is_representative') ? $request->taker_nik : null,
            'picked_up_at' => now(),
            'released_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'KTP berhasil diambil.');
    }
}
