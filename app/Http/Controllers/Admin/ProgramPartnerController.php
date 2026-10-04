<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DeletesStoredMedia;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProgramPartnerController extends Controller
{
    use DeletesStoredMedia;

    public function index(Program $program): View
    {
        $items = $program->partners()->orderBy('order_number', 'asc')->get();

        return view('admin.eduroute.partners.index', compact('program', 'items'));
    }

    public function create(Program $program): View
    {
        return view('admin.eduroute.partners.form', [
            'program' => $program,
            'item' => null,
            'action' => route('admin.eduroute.partners.store', $program),
            'method' => 'POST',
            'submitLabel' => 'Simpan Mitra',
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:500',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_url' => 'nullable|url|max:500',
            'logo_bg_color' => 'nullable|string|max:30',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $program->partners()->create([
            'name' => $validated['name'],
            'website_url' => $validated['website_url'] ?? null,
            'logo' => $request->hasFile('logo') ? $request->file('logo')->store('partners', 'public') : ($validated['logo_url'] ?? null),
            'logo_bg_color' => $this->validatedLogoBgColor($validated['logo_bg_color'] ?? null),
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.eduroute.partners.index', $program)->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function edit(Program $program, int $partner): View
    {
        $item = $program->partners()->findOrFail($partner);

        return view('admin.eduroute.partners.form', [
            'program' => $program,
            'item' => $item,
            'action' => route('admin.eduroute.partners.update', [$program, $item->id]),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Mitra',
        ]);
    }

    public function update(Request $request, Program $program, int $partner): RedirectResponse
    {
        $item = $program->partners()->findOrFail($partner);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:500',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_url' => 'nullable|url|max:500',
            'logo_bg_color' => 'nullable|string|max:30',
            'order_number' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $attributes = [
            'name' => $validated['name'],
            'website_url' => $validated['website_url'] ?? null,
            'logo_bg_color' => $this->validatedLogoBgColor($validated['logo_bg_color'] ?? null),
            'order_number' => $validated['order_number'],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('logo')) {
            $this->updateWithStoredMedia($item, $attributes, 'logo', $request->file('logo'), 'partners');
        } else {
            $oldLogo = $item->logo;
            if (! empty($validated['logo_url'])) {
                $attributes['logo'] = $validated['logo_url'];
            }

            $item->update($attributes);

            if (! empty($validated['logo_url'])) {
                $this->deleteStoredMedia($oldLogo);
            }
        }

        return redirect()->route('admin.eduroute.partners.index', $program)->with('success', 'Mitra berhasil diperbarui.');
    }

    public function destroy(Program $program, int $partner): RedirectResponse
    {
        $item = $program->partners()->findOrFail($partner);
        $logoPath = $item->logo;
        $item->delete();
        $this->deleteStoredMedia($logoPath);

        return redirect()->route('admin.eduroute.partners.index', $program)->with('success', 'Mitra berhasil dihapus.');
    }

    /**
     * Warna latar logo boleh ditulis sebagai "#RRGGBB", "RRGGBB", "#RGB",
     * atau "rgb(r, g, b)". Nilai yang tidak dikenali ditolak dengan pesan
     * yang jelas, bukan diam-diam diabaikan.
     */
    private function validatedLogoBgColor(?string $color): ?string
    {
        if ($color === null || trim($color) === '') {
            return null;
        }

        $normalized = Partner::normalizeHexColor($color);

        if ($normalized === null) {
            throw ValidationException::withMessages([
                'logo_bg_color' => 'Warna latar tidak dikenali. Gunakan format #RRGGBB, RRGGBB, atau rgb(r, g, b).',
            ]);
        }

        return $normalized;
    }
}
