<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DeletesStoredMedia;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgramTeacherController extends Controller
{
    use DeletesStoredMedia;

    public function index(Program $program): View
    {
        $items = $program->teachers()->orderBy('order_number', 'asc')->get();

        return view('admin.eduroute.teachers.index', compact('program', 'items'));
    }

    public function create(Program $program): View
    {
        return view('admin.eduroute.teachers.form', [
            'program' => $program,
            'item' => null,
            'submissionToken' => (string) Str::uuid(),
            'action' => route('admin.eduroute.teachers.store', $program),
            'method' => 'POST',
            'submitLabel' => 'Simpan Guru',
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $submissionToken = $request->validate([
            'submission_token' => ['required', 'uuid'],
        ])['submission_token'];

        $tokenKey = "eduroute:teacher-submit:{$program->id}:{$submissionToken}";

        if (! Cache::add($tokenKey, true, now()->addMinutes(10))) {
            return redirect()->route('admin.eduroute.teachers.index', $program)
                ->with('success', 'Data guru tersebut sudah berhasil disimpan.');
        }

        $program->teachers()->create([
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'role_title' => $validated['role_title'] ?? null,
            'description' => $validated['description'] ?? null,
            'expertise' => $validated['expertise'] ?? null,
            'education' => $validated['education'] ?? null,
            'campus' => $validated['campus'] ?? null,
            'photo' => $request->hasFile('photo') ? $request->file('photo')->store('teachers', 'public') : null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.teachers.index', $program)->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Program $program, int $teacher): View
    {
        $item = $program->teachers()->findOrFail($teacher);

        return view('admin.eduroute.teachers.form', [
            'program' => $program,
            'item' => $item,
            'action' => route('admin.eduroute.teachers.update', [$program, $item->id]),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Guru',
        ]);
    }

    public function update(Request $request, Program $program, int $teacher): RedirectResponse
    {
        $item = $program->teachers()->findOrFail($teacher);

        $validated = $request->validate($this->rules());

        $this->updateWithStoredMedia($item, [
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'role_title' => $validated['role_title'] ?? null,
            'description' => $validated['description'] ?? null,
            'expertise' => $validated['expertise'] ?? null,
            'education' => $validated['education'] ?? null,
            'campus' => $validated['campus'] ?? null,
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ], 'photo', $request->file('photo'), 'teachers');

        return redirect()->route('admin.eduroute.teachers.index', $program)->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Program $program, int $teacher): RedirectResponse
    {
        $item = $program->teachers()->findOrFail($teacher);

        $photoPath = $item->photo;
        $item->delete();
        $this->deleteStoredMedia($photoPath);

        return redirect()->route('admin.eduroute.teachers.index', $program)->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Aturan validasi yang dipakai bersama oleh store() dan update().
     *
     * @return array<string, array<int, string>|string>
     */
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'role_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'expertise' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'campus' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }
}
