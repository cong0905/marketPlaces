<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $reports = Report::where('status', $status)
            ->with(['reporter', 'reportable'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function resolve(Request $request, Report $report)
    {
        $request->validate([
            'admin_note' => 'required|string|max:500',
            'action' => 'required|in:warn_user,hide_product,dismiss',
        ]);

        // Logic xử lý vi phạm dựa theo action (tạm thời ghi log hoặc note)
        if ($request->action === 'hide_product' && $report->reportable_type === 'App\Models\Product') {
            $report->reportable->update(['status' => 'hidden', 'rejection_reason' => 'Bị ẩn do vi phạm: ' . $request->admin_note]);
        }

        $report->update([
            'status' => 'resolved',
            'admin_note' => $request->admin_note,
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Đã xử lý báo cáo thành công.');
    }
}
