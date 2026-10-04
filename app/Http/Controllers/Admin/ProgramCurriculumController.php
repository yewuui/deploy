<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DeletesStoredMedia;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramCurriculumController extends Controller
{
    use DeletesStoredMedia;

    /**
     * Kelas yang dikelola pada kurikulum.
     *
     * @var array<int, int>
     */
    private const GRADES = [10, 11, 12];

    public function index(Request $request, Program $program): View
    {
        $activeGrade = $this->resolveGrade($request);

        // Satu query agregat untuk jumlah materi/kegiatan tiap kelas (dipakai label tab)
        $countsByGrade = $program->curriculums()
            ->selectRaw('grade, count(*) as aggregate')
            ->groupBy('grade')
            ->pluck('aggregate', 'grade');

        $gradeTabs = array_map(fn (int $grade): array => [
            'grade' => $grade,
            'count' => (int) ($countsByGrade[$grade] ?? 0),
            'is_active' => $grade === $activeGrade,
        ], self::GRADES);

        // Ambil item untuk kelas aktif
        $items = $program->curriculums()
            ->where('grade', $activeGrade)
            ->orderBy('order_number', 'asc')
            ->get();

        return view('admin.eduroute.curriculum.index', compact(
            'program',
            'activeGrade',
            'gradeTabs',
            'items'
        ));
    }

    public function create(Request $request, Program $program): View
    {
        $activeGrade = $this->resolveGrade($request);

        return view('admin.eduroute.curriculum.form', [
            'program' => $program,
            'item' => null,
            'activeGrade' => $activeGrade,
            'action' => route('admin.eduroute.curriculum.store', $program),
            'method' => 'POST',
            'submitLabel' => $activeGrade === 12 ? 'Simpan Program / Kegiatan' : 'Simpan Materi',
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $grade = (int) $request->input('grade', 10);

        if ($grade === 12) {
            $validated = $request->validate([
                'grade' => 'required|integer|in:10,11,12',
                'type' => 'required|string|in:pkl,proyek,ukk,kesiapan_kerja,dokumentasi',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'topics' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'order_number' => 'required|integer|min:0',
            ]);

            $imagePath = $request->hasFile('image')
                ? $request->file('image')->store('curriculums', 'public')
                : null;

            $program->curriculums()->create([
                'grade' => 12,
                'type' => $validated['type'],
                'semester' => 1,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'topics' => $this->parseTopics($validated['topics'] ?? ''),
                'image' => $imagePath,
                'week_start' => 1,
                'week_end' => 1,
                'color_theme' => 'blue',
                'order_number' => $validated['order_number'],
            ]);

            return redirect()
                ->route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => 12])
                ->with('success', 'Program / kegiatan Kelas 12 berhasil ditambahkan.');
        }

        $validated = $request->validate([
            'grade' => 'required|integer|in:10,11',
            'semester' => 'required|integer|in:1,2',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topics' => 'required|string',
            'order_number' => 'required|integer|min:0',
        ]);

        $program->curriculums()->create([
            'grade' => $validated['grade'],
            'type' => 'materi',
            'semester' => $validated['semester'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'topics' => $this->parseTopics($validated['topics']),
            'week_start' => 1,
            'week_end' => 1,
            'color_theme' => 'blue',
            'order_number' => $validated['order_number'],
        ]);

        return redirect()
            ->route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => $validated['grade']])
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Request $request, Program $program, int $curriculum): View
    {
        $item = $program->curriculums()->findOrFail($curriculum);
        $grade = (int) $item->grade;

        return view('admin.eduroute.curriculum.form', [
            'program' => $program,
            'item' => $item,
            'activeGrade' => $this->resolveGrade($request, $grade),
            'action' => route('admin.eduroute.curriculum.update', [$program, $item->id]),
            'method' => 'PUT',
            'submitLabel' => $grade === 12 ? 'Perbarui Program / Kegiatan' : 'Perbarui Materi',
        ]);
    }

    public function update(Request $request, Program $program, int $curriculum): RedirectResponse
    {
        $item = $program->curriculums()->findOrFail($curriculum);
        $grade = (int) $request->input('grade', $item->grade);

        if ($grade === 12) {
            $validated = $request->validate([
                'grade' => 'required|integer|in:10,11,12',
                'type' => 'required|string|in:pkl,proyek,ukk,kesiapan_kerja,dokumentasi',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'topics' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'order_number' => 'required|integer|min:0',
            ]);

            $this->updateWithStoredMedia($item, [
                'grade' => 12,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'topics' => $this->parseTopics($validated['topics'] ?? ''),
                'order_number' => $validated['order_number'],
            ], 'image', $request->file('image'), 'curriculums');

            return redirect()
                ->route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => 12])
                ->with('success', 'Program / kegiatan Kelas 12 berhasil diperbarui.');
        }

        $validated = $request->validate([
            'grade' => 'required|integer|in:10,11',
            'semester' => 'required|integer|in:1,2',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topics' => 'required|string',
            'order_number' => 'required|integer|min:0',
        ]);

        $imageToDelete = (int) $item->grade === 12 ? $item->image : null;

        $item->update([
            'grade' => $validated['grade'],
            'type' => 'materi',
            'semester' => $validated['semester'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'topics' => $this->parseTopics($validated['topics']),
            'image' => null,
            'week_start' => $item->week_start ?? 1,
            'week_end' => $item->week_end ?? 1,
            'order_number' => $validated['order_number'],
        ]);

        $this->deleteStoredMedia($imageToDelete);

        return redirect()
            ->route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => $validated['grade']])
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Program $program, int $curriculum): RedirectResponse
    {
        $item = $program->curriculums()->findOrFail($curriculum);
        $grade = (int) $item->grade;
        $imagePath = $item->image;

        $item->delete();
        $this->deleteStoredMedia($imagePath);

        $message = $grade === 12 ? 'Program / kegiatan Kelas 12 berhasil dihapus.' : 'Materi berhasil dihapus.';

        return redirect()
            ->route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => $grade])
            ->with('success', $message);
    }

    private function parseTopics(string $topics): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $topics)), function (string $topic): bool {
            return $topic !== '';
        }));
    }

    /**
     * Ambil kelas aktif dari query string (?kelas=). Nilai yang tidak valid
     * jatuh ke $default agar URL yang salah tidak menghasilkan halaman kosong.
     */
    private function resolveGrade(Request $request, int $default = 10): int
    {
        if (! in_array($default, self::GRADES, true)) {
            $default = self::GRADES[0];
        }

        $grade = (int) $request->query('kelas', $default);

        return in_array($grade, self::GRADES, true) ? $grade : $default;
    }
}
