<?php

namespace App\Providers;

use App\Models\Achievement;
use App\Models\Article;
use App\Models\BludUnit;
use App\Models\Download;
use App\Models\Ekstrakurikuler;
use App\Models\OrganisasiSiswa;
use App\Models\PklPlace;
use App\Models\PpdbProgram;
use App\Models\PpdbStep;
use App\Models\Program;
use App\Models\ProgramKeahlian;
use App\Models\SaranaPrasarana;
use App\Models\SiteSetting;
use App\Models\SiteStatistic;
use App\Models\TeachingFactory;
use App\Support\SchoolKnowledgeBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SchoolKnowledgeBase::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->shareEduroutePrograms();

        foreach ($this->chatbotKnowledgeModels() as $model) {
            $model::saved(fn () => app(SchoolKnowledgeBase::class)->flush());
            $model::deleted(fn () => app(SchoolKnowledgeBase::class)->flush());
        }
    }

    /**
     * Daftar jurusan aktif untuk pemilih jurusan di header EduRoute.
     *
     * Dipasang lewat view composer, bukan dikirim manual dari setiap action,
     * supaya seluruh halaman EduRoute mendapat daftar yang sama tanpa perlu
     * mengingat menambahkannya lagi saat halaman baru ditambahkan.
     */
    private function shareEduroutePrograms(): void
    {
        View::composer('layouts.eduroute', function ($view): void {
            $view->with('eduroutePrograms', Program::query()
                ->active()
                ->with('programKeahlian')
                ->leftJoin('program_keahlians', 'programs.program_keahlian_id', '=', 'program_keahlians.id')
                ->orderByRaw('CASE WHEN program_keahlians.sort_order IS NULL THEN 1 ELSE 0 END ASC')
                ->orderBy('program_keahlians.sort_order', 'asc')
                ->orderByRaw('COALESCE(program_keahlians.title, programs.name) ASC')
                ->select('programs.*')
                ->get());
        });
    }

    /**
     * @return list<class-string<Model>>
     */
    private function chatbotKnowledgeModels(): array
    {
        return [
            Achievement::class,
            Article::class,
            BludUnit::class,
            Download::class,
            Ekstrakurikuler::class,
            OrganisasiSiswa::class,
            PklPlace::class,
            PpdbProgram::class,
            PpdbStep::class,
            ProgramKeahlian::class,
            SaranaPrasarana::class,
            SiteSetting::class,
            SiteStatistic::class,
            TeachingFactory::class,
        ];
    }
}
