<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_type' => 'required|in:App\Models\Book,App\Models\User',
            'reportable_id' => 'required|integer',
            'type' => 'required|in:inappropriate_content,spam,fake_book,user_behavior,other',
            'description' => 'required|string|max:1000',
        ]);

        // Check if user already reported this item
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', $request->reportable_type)
            ->where('reportable_id', $request->reportable_id)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            return redirect()->back()->with('error', 'You have already reported this item.');
        }

        Report::create([
            'reporter_id' => Auth::id(),
            'reportable_type' => $request->reportable_type,
            'reportable_id' => $request->reportable_id,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully!');
    }

    public function index()
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->route('books.index');
        }

        $reports = Report::with(['reporter'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->route('books.index');
        }

        $report->load(['reporter']);
        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,investigating,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        if (in_array($request->status, ['resolved', 'dismissed'])) {
            $data['resolved_by'] = Auth::guard('admin')->id();
            $data['resolved_at'] = now();
        }

        $report->update($data);

        return redirect()->back()->with('success', 'Report status updated successfully!');
    }

    public function deleteReportedItem(Report $report)
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($report->reportable_type === Book::class) {
            $book = Book::find($report->reportable_id);
            if ($book) {
                $book->delete();
            }
            $report->update([
                'status' => 'resolved',
                'admin_notes' => trim(($report->admin_notes ?? '') . "\nItem deleted by admin."),
                'resolved_by' => Auth::guard('admin')->id(),
                'resolved_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Reported book deleted and report resolved.');
        }

        return redirect()->back()->with('error', 'Only reported books can be deleted via this action.');
    }
}
