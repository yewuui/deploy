<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\View\View;

class EdurouteDashboardController extends Controller
{
    public function index(): View
    {
        $programs = Program::withCount(['curriculums', 'teachers'])
            ->orderBy('short_name', 'asc')
            ->get();

        return view('admin.eduroute.index', compact('programs'));
    }

    public function show(Program $program): View
    {
        $program->loadCount([
            'curriculums',
            'teachers',
            'careers',
            'achievements',
            'media',
            'partners',
        ]);

        return view('admin.eduroute.show', compact('program'));
    }
}
