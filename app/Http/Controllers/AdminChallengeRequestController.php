<?php

namespace App\Http\Controllers;

use App\Models\ChallengeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminChallengeRequestController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');

        $query = ChallengeRequest::with(['user', 'reviewer'])->latest();

        if (in_array($statusFilter, ['pending', 'reviewing', 'approved', 'rejected'])) {
            $query->where('status', $statusFilter);
        }

        $requests = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => ChallengeRequest::count(),
            'pending'   => ChallengeRequest::where('status', 'pending')->count(),
            'reviewing' => ChallengeRequest::where('status', 'reviewing')->count(),
            'approved'  => ChallengeRequest::where('status', 'approved')->count(),
            'rejected'  => ChallengeRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.challenge_requests.index', compact('requests', 'stats', 'statusFilter'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $challengeRequest = ChallengeRequest::findOrFail($id);

        $validated = $request->validate([
            'status'      => ['required', 'in:pending,reviewing,approved,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $challengeRequest->update([
            'status'      => $validated['status'],
            'admin_notes' => !empty($validated['admin_notes']) ? trim($validated['admin_notes']) : null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Status pengajuan tantangan #' . $challengeRequest->id . ' berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $challengeRequest = ChallengeRequest::findOrFail($id);
        $title = $challengeRequest->title;
        $challengeRequest->delete();

        return back()->with('success', 'Pengajuan tantangan "' . e($title) . '" telah dihapus dari sistem.');
    }
}
