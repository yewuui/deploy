<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\SiteStatistic;
use App\Support\Site;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $articles = Article::published()->latestPublished()->limit(4)->get();
        $achievements = Achievement::published()->ordered()->limit(6)->get();
        $statistics = SiteStatistic::ordered()->get();
        $heroImages = Gallery::published()->ordered()->group('hero')->get();

        return view('home', [
            'articles' => $articles,
            'achievements' => $achievements,
            'statistics' => $statistics,
            'heroImages' => $heroImages,
            'principal' => Site::principal(),
        ]);
    }
}
