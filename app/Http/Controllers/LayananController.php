<?php

namespace App\Http\Controllers;

use App\Models\BludUnit;
use App\Models\PklPlace;
use App\Models\TeachingFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class LayananController extends Controller
{
    public function blud(): View
    {
        $serviceData = json_decode(
            file_get_contents(database_path('data/layanan.json')),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        $unitDetails = collect($serviceData['blud_unit'] ?? [])
            ->keyBy('name');

        return view('blud', [
            'units' => BludUnit::published()->ordered()->whereNotNull('image')->get(),
            'unitDetails' => $unitDetails,
        ]);
    }

    public function pkl(): View
    {
        $places = $this->placesPerProgram();

        return view('pkl', [
            'partners' => TeachingFactory::published()
                ->with('programKeahlian')
                ->ordered()
                ->get(),
            'places' => $places,
            'placeCount' => $places->sum(fn (array $group): int => $group['places']->count()),
        ]);
    }

    /**
     * Tempat PKL dikelompokkan per program keahlian supaya tiap program punya
     * subjudul sendiri di halaman.
     *
     * @return Collection<int, array{title: string, places: Collection<int, PklPlace>}>
     */
    private function placesPerProgram(): Collection
    {
        return PklPlace::published()
            ->with('programKeahlian')
            ->ordered()
            ->get()
            ->groupBy(fn (PklPlace $place): string => (string) $place->programKeahlian?->title)
            ->map(fn (Collection $places, string $title): array => [
                'title' => $title === '' ? 'Program Lainnya' : $title,
                'places' => $places->values(),
            ])
            ->values();
    }
}
