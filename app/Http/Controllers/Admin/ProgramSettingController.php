<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramSettingController extends Controller
{
    public function edit(Program $program): View
    {
        return view('admin.eduroute.settings.edit', compact('program'));
    }

    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:20',
            'slug' => ['required', 'alpha_dash', 'max:50', Rule::unique('programs', 'slug')->ignore($program->id)],
            'description' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $program->update([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'hero_title' => $validated['hero_title'] ?? null,
            'hero_text' => $validated['hero_text'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.show', $program->fresh())->with('success', 'Identitas jurusan berhasil diperbarui.');
    }
}
