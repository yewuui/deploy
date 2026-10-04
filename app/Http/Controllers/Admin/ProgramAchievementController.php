<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DeletesStoredMedia;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramAchievementController extends Controller
{
    use DeletesStoredMedia;

    public function index(Program $program): View
    {
        $items = $program->achievements()->orderBy('order_number', 'asc')->get();

        return view('admin.eduroute.achievements.index', compact('program', 'items'));
    }

    public function create(Program $program): View
    {
        return view('admin.eduroute.achievements.form', [
            'program' => $program,
            'item' => null,
            'action' => route('admin.eduroute.achievements.store', $program),
            'method' => 'POST',
            'submitLabel' => 'Simpan Prestasi',
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'date_label' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $program->achievements()->create([
            'title' => $validated['title'],
            'name' => $validated['name'] ?? null,
            'date_label' => $validated['date_label'] ?? null,
            'category' => $validated['category'] ?? null,
            'level' => $validated['level'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $request->hasFile('image') ? $request->file('image')->store('achievements', 'public') : null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.achievements.index', $program)->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Program $program, int $achievement): View
    {
        $item = $program->achievements()->findOrFail($achievement);

        return view('admin.eduroute.achievements.form', [
            'program' => $program,
            'item' => $item,
            'action' => route('admin.eduroute.achievements.update', [$program, $item->id]),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Prestasi',
        ]);
    }

    public function update(Request $request, Program $program, int $achievement): RedirectResponse
    {
        $item = $program->achievements()->findOrFail($achievement);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'date_label' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $this->updateWithStoredMedia($item, [
            'title' => $validated['title'],
            'name' => $validated['name'] ?? null,
            'date_label' => $validated['date_label'] ?? null,
            'category' => $validated['category'] ?? null,
            'level' => $validated['level'] ?? null,
            'description' => $validated['description'] ?? null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ], 'image', $request->file('image'), 'achievements');

        return redirect()->route('admin.eduroute.achievements.index', $program)->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Program $program, int $achievement): RedirectResponse
    {
        $item = $program->achievements()->findOrFail($achievement);
        $imagePath = $item->image;
        $item->delete();
        $this->deleteStoredMedia($imagePath);

        return redirect()->route('admin.eduroute.achievements.index', $program)->with('success', 'Prestasi berhasil dihapus.');
    }
}
