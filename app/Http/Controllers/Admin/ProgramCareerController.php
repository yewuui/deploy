<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DeletesStoredMedia;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramCareerController extends Controller
{
    use DeletesStoredMedia;

    public function index(Program $program): View
    {
        $items = $program->careers()->orderBy('order_number', 'asc')->get();

        return view('admin.eduroute.careers.index', compact('program', 'items'));
    }

    public function create(Program $program): View
    {
        return view('admin.eduroute.careers.form', [
            'program' => $program,
            'item' => null,
            'action' => route('admin.eduroute.careers.store', $program),
            'method' => 'POST',
            'submitLabel' => 'Simpan Karier',
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'tasks' => 'nullable|string',
            'tools' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $program->careers()->create([
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'details' => $validated['details'] ?? null,
            'tasks' => $this->parseList($validated['tasks'] ?? null),
            'tools' => $this->parseList($validated['tools'] ?? null),
            'icon' => $request->hasFile('icon') ? $request->file('icon')->store('careers', 'public') : null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.careers.index', $program)->with('success', 'Prospek karier berhasil ditambahkan.');
    }

    public function edit(Program $program, int $career): View
    {
        $item = $program->careers()->findOrFail($career);

        return view('admin.eduroute.careers.form', [
            'program' => $program,
            'item' => $item,
            'action' => route('admin.eduroute.careers.update', [$program, $item->id]),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Karier',
        ]);
    }

    public function update(Request $request, Program $program, int $career): RedirectResponse
    {
        $item = $program->careers()->findOrFail($career);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'tasks' => 'nullable|string',
            'tools' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $this->updateWithStoredMedia($item, [
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'details' => $validated['details'] ?? null,
            'tasks' => $this->parseList($validated['tasks'] ?? null),
            'tools' => $this->parseList($validated['tools'] ?? null),
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ], 'icon', $request->file('icon'), 'careers');

        return redirect()->route('admin.eduroute.careers.index', $program)->with('success', 'Prospek karier berhasil diperbarui.');
    }

    public function destroy(Program $program, int $career): RedirectResponse
    {
        $item = $program->careers()->findOrFail($career);
        $iconPath = $item->icon;
        $item->delete();
        $this->deleteStoredMedia($iconPath);

        return redirect()->route('admin.eduroute.careers.index', $program)->with('success', 'Prospek karier berhasil dihapus.');
    }

    private function parseList(?string $value): ?array
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $items = array_values(array_filter(array_map('trim', explode(',', $value)), function (string $item): bool {
            return $item !== '';
        }));

        return $items === [] ? null : $items;
    }
}
