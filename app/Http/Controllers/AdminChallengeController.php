<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminChallengeController extends Controller
{
    public function index(): View
    {
        $challenges = Challenge::withCount('solves')
            ->orderBy('category')
            ->orderBy('points')
            ->get();

        return view('admin.challenges.index', compact('challenges'));
    }

    public function create(): View
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:challenges,slug'],
            'category'    => ['required', 'in:web,crypto,recon,general'],
            'difficulty'  => ['required', 'in:easy,medium,hard'],
            'points'      => ['required', 'integer', 'min:1'],
            'flag'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'hint'        => ['nullable', 'string'],
            'remediation' => ['nullable', 'string'],
            'mode_target' => ['required', 'in:competition,training,both'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Challenge::create($validated);

        return redirect()->route('admin.challenges.index')->with('success', 'Tantangan baru berhasil dibuat.');
    }

    public function show(Challenge $challenge): View
    {
        $challenge->load(['solves.user']);
        return view('admin.challenges.show', compact('challenge'));
    }

    public function edit(Challenge $challenge): View
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:challenges,slug,' . $challenge->id],
            'category'    => ['required', 'in:web,crypto,recon,general'],
            'difficulty'  => ['required', 'in:easy,medium,hard'],
            'points'      => ['required', 'integer', 'min:1'],
            'flag'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'hint'        => ['nullable', 'string'],
            'remediation' => ['nullable', 'string'],
            'mode_target' => ['required', 'in:competition,training,both'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $challenge->update($validated);

        return redirect()->route('admin.challenges.index')->with('success', 'Tantangan berhasil diperbarui.');
    }

    public function destroy(Challenge $challenge): RedirectResponse
    {
        $title = $challenge->title;
        $challenge->delete();

        return redirect()->route('admin.challenges.index')->with('success', "Tantangan '{$title}' berhasil dihapus.");
    }
}

