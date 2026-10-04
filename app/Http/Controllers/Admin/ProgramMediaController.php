<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DeletesStoredMedia;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramMediaController extends Controller
{
    use DeletesStoredMedia;

    public function index(Program $program): View
    {
        $items = $program->media()->orderBy('order_number', 'asc')->get();

        return view('admin.eduroute.media.index', compact('program', 'items'));
    }

    public function create(Program $program): View
    {
        return view('admin.eduroute.media.form', [
            'program' => $program,
            'item' => null,
            'action' => route('admin.eduroute.media.store', $program),
            'method' => 'POST',
            'submitLabel' => 'Simpan Media',
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'handle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $program->media()->create([
            'platform' => $validated['platform'],
            'handle' => $validated['handle'] ?? null,
            'description' => $validated['description'] ?? null,
            'url' => $validated['url'] ?? null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.media.index', $program)->with('success', 'Media berhasil ditambahkan.');
    }

    public function edit(Program $program, int $medium): View
    {
        $item = $program->media()->findOrFail($medium);

        return view('admin.eduroute.media.form', [
            'program' => $program,
            'item' => $item,
            'action' => route('admin.eduroute.media.update', [$program, $item->id]),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Media',
        ]);
    }

    public function update(Request $request, Program $program, int $medium): RedirectResponse
    {
        $item = $program->media()->findOrFail($medium);

        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'handle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $item->update([
            'platform' => $validated['platform'],
            'handle' => $validated['handle'] ?? null,
            'description' => $validated['description'] ?? null,
            'url' => $validated['url'] ?? null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.media.index', $program)->with('success', 'Media berhasil diperbarui.');
    }

    public function destroy(Program $program, int $medium): RedirectResponse
    {
        $item = $program->media()->findOrFail($medium);
        $imagePath = $item->image;
        $item->delete();
        $this->deleteStoredMedia($imagePath);

        return redirect()->route('admin.eduroute.media.index', $program)->with('success', 'Media berhasil dihapus.');
    }
}
