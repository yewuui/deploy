<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EdurouteController extends Controller
{
    private function resolveProgram(string $slug): Program
    {
        return Program::active()->where('slug', $slug)->firstOrFail();
    }

    public function dashboard(string $program): View
    {
        $program = $this->resolveProgram($program);

        $teachers = $program->teachers()->where('is_active', true)->orderBy('order_number', 'asc')->get();
        $partners = $program->partners()->where('is_active', true)->orderBy('order_number', 'asc')->get();

        return view('eduroute.dashboard', compact('program', 'teachers', 'partners'));
    }

    public function roadmap(Request $request, string $program): View
    {
        $program = $this->resolveProgram($program);

        $requestedGrade = filter_var($request->query('grade', 10), FILTER_VALIDATE_INT);
        $activeGrade = in_array($requestedGrade, [10, 11, 12], true) ? $requestedGrade : 10;

        // Presentasi publik: satu perjalanan kontinu per kelas, diurutkan
        // order_number. Kolom semester tetap ada di data (untuk admin CMS)
        // tetapi tidak lagi memengaruhi query maupun tampilan publik.
        $stages = $program->curriculums()->where('grade', $activeGrade)->orderBy('order_number', 'asc')->get();
        $totalStages = $stages->count();

        return view('eduroute.roadmap', compact(
            'program',
            'activeGrade',
            'stages',
            'totalStages'
        ));
    }

    public function prospek(string $program): View
    {
        $program = $this->resolveProgram($program);

        $careers = $program->careers()->where('is_active', true)->orderBy('order_number', 'asc')->get();

        return view('eduroute.prospek', compact('program', 'careers'));
    }

    public function prestasi(string $program): View
    {
        $program = $this->resolveProgram($program);

        $achievements = $program->achievements()->where('is_active', true)->orderBy('order_number', 'asc')->get();

        return view('eduroute.prestasi', compact('program', 'achievements'));
    }

    public function media(string $program): View
    {
        $program = $this->resolveProgram($program);

        $socialMedia = $program->media()->where('is_active', true)->orderBy('order_number', 'asc')->get();

        return view('eduroute.media', compact('program', 'socialMedia'));
    }
}
