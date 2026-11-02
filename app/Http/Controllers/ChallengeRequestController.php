<?php

namespace App\Http\Controllers;

use App\Models\ChallengeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChallengeRequestController extends Controller
{
    /**
     * Tampilkan halaman Request Tantangan.
     */
    public function index(): View
    {
        $user = Auth::user();
        $userRequests = collect();

        if ($user) {
            $userRequests = ChallengeRequest::where('user_id', $user->id)
                ->latest()
                ->get();
        }

        // Email kontak langsung sesuai permintaan
        $contactEmail = 'saresonel123@gmail.com';
        $emailSubject = 'Request Tantangan Baru ITEC CyberLab';
        $emailBody = "Halo Tim ITEC CyberLab,\n\nSaya ingin mengajukan tantangan baru:\n\nJudul:\nKategori:\nTingkat Kesulitan:\nPenjelasan Singkat:\nFlag Usulan (format FLAG{...}):\n\nTerima kasih,\n" . ($user ? $user->name : '');

        $mailtoLink = 'mailto:' . $contactEmail . '?subject=' . rawurlencode($emailSubject) . '&body=' . rawurlencode($emailBody);

        return view('challenge_requests.index', compact(
            'user',
            'userRequests',
            'contactEmail',
            'mailtoLink'
        ));
    }

    /**
     * Simpan pengajuan tantangan beserta upload file.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'author_name'    => ['required', 'string', 'max:100'],
            'author_email'   => ['required', 'email', 'max:150'],
            'title'          => ['required', 'string', 'min:3', 'max:150'],
            'category'       => ['required', 'string', 'in:web,crypto,recon,forensics,reversing,pwn,general'],
            'difficulty'     => ['required', 'string', 'in:easy,medium,hard,insane'],
            'description'    => ['required', 'string', 'min:5', 'max:5000'],
            'hint'           => ['nullable', 'string', 'max:1000'],
            'reference_url'  => ['nullable', 'string', 'max:255'],
            'suggested_flag' => ['nullable', 'string', 'max:255'],
            'hint_file'      => ['nullable', 'file', 'mimes:pdf', 'max:15360'], // max 15MB
            'challenge_file' => ['nullable', 'file', 'max:30720'], // max 30MB
        ], [
            'author_name.required'  => 'Nama pengusul harus diisi.',
            'author_email.required' => 'Email harus diisi.',
            'author_email.email'    => 'Format email tidak sesuai.',
            'title.required'        => 'Judul tantangan harus diisi.',
            'category.required'     => 'Pilih kategori tantangan.',
            'difficulty.required'   => 'Pilih tingkat kesulitan.',
            'description.required'  => 'Deskripsi tantangan harus diisi.',
            'description.min'       => 'Deskripsi tantangan terlalu singkat.',
            'hint_file.mimes'       => 'File tutorial atau hint harus berformat PDF.',
            'hint_file.max'         => 'Ukuran file PDF maksimal 15MB.',
            'challenge_file.max'    => 'Ukuran file tantangan maksimal 30MB.',
        ]);

        $hintFilePath = null;
        $originalHintFilename = null;
        if ($request->hasFile('hint_file') && $request->file('hint_file')->isValid()) {
            $file = $request->file('hint_file');
            $originalHintFilename = $file->getClientOriginalName();
            $hintFilePath = $file->store('challenge_requests/hints', 'public');
        }

        $challengeFilePath = null;
        $originalChallengeFilename = null;
        if ($request->hasFile('challenge_file') && $request->file('challenge_file')->isValid()) {
            $file = $request->file('challenge_file');
            $originalChallengeFilename = $file->getClientOriginalName();
            $challengeFilePath = $file->store('challenge_requests/challenges', 'public');
        }

        $challengeRequest = ChallengeRequest::create([
            'user_id'                     => Auth::id(),
            'author_name'                 => trim($validated['author_name']),
            'author_email'                => trim(strtolower($validated['author_email'])),
            'title'                       => trim($validated['title']),
            'category'                    => $validated['category'],
            'difficulty'                  => $validated['difficulty'],
            'description'                 => trim($validated['description']),
            'hint'                        => !empty($validated['hint']) ? trim($validated['hint']) : null,
            'hint_file'                   => $hintFilePath,
            'original_hint_filename'      => $originalHintFilename,
            'challenge_file'              => $challengeFilePath,
            'original_challenge_filename' => $originalChallengeFilename,
            'reference_url'               => !empty($validated['reference_url']) ? trim($validated['reference_url']) : null,
            'suggested_flag'              => !empty($validated['suggested_flag']) ? trim($validated['suggested_flag']) : null,
            'status'                      => 'pending',
        ]);

        return redirect()->route('challenge_requests.index')
            ->with('success', 'Request tantangan "' . e($challengeRequest->title) . '" berhasil dikirim dan tersimpan.');
    }
}
