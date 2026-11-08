<?php

namespace App\Http\Controllers;

use App\Models\ChallengeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChallengeRequestController extends Controller
{
    /**
     * Tampilkan halaman Request Tantangan.
     */
    public function index(Request $request): Response|View
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

        // Pastikan cookie itec_device_id terpasang pada pengunjung
        $deviceToken = $request->cookie('itec_device_id');
        $view = view('challenge_requests.index', compact(
            'user',
            'userRequests',
            'contactEmail',
            'mailtoLink'
        ));

        if (empty($deviceToken)) {
            $deviceToken = (string) Str::uuid();
            return response($view)->cookie('itec_device_id', $deviceToken, 60 * 24 * 365);
        }

        return $view;
    }

    /**
     * Simpan pengajuan tantangan beserta upload file dengan proteksi rate limit (10 menit per IP & Device).
     */
    public function store(Request $request): RedirectResponse
    {
        $ip = $request->ip();
        $deviceToken = $request->cookie('itec_device_id');
        if (empty($deviceToken)) {
            $deviceToken = (string) Str::uuid();
        }

        // Rate limit: 1 pengajuan per 10 menit (600 detik) untuk tiap IP dan Device
        $cooldownSeconds = 600;
        $tenMinutesAgo = now()->subSeconds($cooldownSeconds);

        $recentSubmission = ChallengeRequest::where(function ($query) use ($ip, $deviceToken) {
            $query->where('submission_ip', $ip);
            if (!empty($deviceToken)) {
                $query->orWhere('device_fingerprint', $deviceToken);
            }
        })->where('created_at', '>=', $tenMinutesAgo)->latest()->first();

        $rateLimitKeyIp = 'challenge_request_ip:' . $ip;
        $rateLimitKeyDevice = 'challenge_request_device:' . $deviceToken;

        $isRateLimited = $recentSubmission
            || RateLimiter::tooManyAttempts($rateLimitKeyIp, 1)
            || RateLimiter::tooManyAttempts($rateLimitKeyDevice, 1);

        if ($isRateLimited) {
            $remainingSeconds = $cooldownSeconds;
            if ($recentSubmission) {
                $elapsed = now()->diffInSeconds($recentSubmission->created_at);
                $remainingSeconds = max(1, $cooldownSeconds - $elapsed);
            } else {
                $remainingSeconds = max(
                    RateLimiter::availableIn($rateLimitKeyIp),
                    RateLimiter::availableIn($rateLimitKeyDevice)
                );
            }
            $remainingMinutes = min(10, max(1, (int) ceil($remainingSeconds / 60)));

            return back()
                ->withInput()
                ->withErrors([
                    'limit' => "Batas pengajuan tercapai: Anda hanya dapat mengirimkan 1 pengajuan per 10 menit untuk tiap IP atau perangkat. Silakan tunggu {$remainingMinutes} menit lagi sebelum mengajukan kembali.",
                ]);
        }

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
            'submission_ip'               => $ip,
            'device_fingerprint'          => $deviceToken,
            'status'                      => 'pending',
        ]);

        // Catat hit pada rate limiter selama 10 menit (600 detik)
        RateLimiter::hit($rateLimitKeyIp, $cooldownSeconds);
        RateLimiter::hit($rateLimitKeyDevice, $cooldownSeconds);

        $redirect = redirect()->route('challenge_requests.index')
            ->with('success', 'Request tantangan "' . e($challengeRequest->title) . '" berhasil dikirim dan tersimpan.');

        if (!$request->hasCookie('itec_device_id')) {
            $redirect->cookie('itec_device_id', $deviceToken, 60 * 24 * 365);
        }

        return $redirect;
    }
}
