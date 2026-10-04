<?php

use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EdurouteDashboardController;
use App\Http\Controllers\Admin\ProgramAchievementController;
use App\Http\Controllers\Admin\ProgramCareerController;
use App\Http\Controllers\Admin\ProgramCurriculumController;
use App\Http\Controllers\Admin\ProgramMediaController;
use App\Http\Controllers\Admin\ProgramPartnerController;
use App\Http\Controllers\Admin\ProgramSettingController;
use App\Http\Controllers\Admin\ProgramTeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EdurouteController;
use App\Http\Controllers\GalleryPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramKeahlianController;
use App\Http\Controllers\SaranaPrasaranaController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sejarah', [ProfileController::class, 'sejarah'])->name('sejarah');
Route::get('/visi-dan-misi', [ProfileController::class, 'visiMisi'])->name('visi-misi');
Route::get('/struktur-organisasi', [ProfileController::class, 'strukturOrganisasi'])->name('struktur-organisasi');

Route::get('/sarana-prasarana', [SaranaPrasaranaController::class, 'index'])->name('sarana-prasarana.index');
Route::get('/sarana-prasarana/{saranaPrasarana}', [SaranaPrasaranaController::class, 'show'])->name('sarana-prasarana.show');

Route::get('/program-keahlian', [ProgramKeahlianController::class, 'index'])->name('program-keahlian.index');
Route::get('/program-keahlian/{programKeahlian}', [ProgramKeahlianController::class, 'show'])->name('program-keahlian.show');

// EduRoute: halaman kurikulum, prospek karier, prestasi, dan media sosial
// per program keahlian. Dipanggil dari dropdown "Program Keahlian".
Route::prefix('eduroute/{program}')->name('eduroute.')->group(function () {
    Route::get('/', [EdurouteController::class, 'dashboard'])->name('dashboard');
    Route::get('/roadmap', [EdurouteController::class, 'roadmap'])->name('roadmap');
    Route::get('/prospek-karier', [EdurouteController::class, 'prospek'])->name('prospek');
    Route::get('/jejak-prestasi', [EdurouteController::class, 'prestasi'])->name('prestasi');
    Route::get('/media-sosial', [EdurouteController::class, 'media'])->name('media');
});

// Redirect 301 dari URL lama EduRoute yang hanya khusus RPL.
$legacyEdurouteRedirects = [
    '/dash' => '/eduroute/rpl',
    '/roadmap' => '/eduroute/rpl/roadmap',
    '/prospek-karir' => '/eduroute/rpl/prospek-karier',
    '/jejak-prestasi' => '/eduroute/rpl/jejak-prestasi',
    '/media-sosial' => '/eduroute/rpl/media-sosial',
];

foreach ($legacyEdurouteRedirects as $from => $to) {
    Route::get($from, function () use ($to) {
        $query = request()->query();

        return redirect()->to($to.($query === [] ? '' : '?'.http_build_query($query)), 301);
    });
}

Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita}', [BeritaController::class, 'show'])->name('berita.show');

Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi');
Route::get('/download', [DownloadController::class, 'index'])->name('download');

Route::get('/ekstrakurikuler', [GalleryPageController::class, 'ekstrakurikuler'])->name('ekstrakurikuler');
Route::get('/organisasi-siswa', [GalleryPageController::class, 'organisasiSiswa'])->name('organisasi-siswa');

Route::get('/blud', [LayananController::class, 'blud'])->name('blud');
Route::get('/pkl', [LayananController::class, 'pkl'])->name('pkl');
Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');

// Redirect 301 dari URL lama Teaching Factory karena isinya kini dilayani
// halaman PKL di bawah menu "Layanan".
Route::get('/teaching-factory', function () {
    return redirect()->to(route('pkl'), 301);
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::post('/chatbot', [ChatController::class, 'store'])
    ->middleware('throttle:'.config('services.chatbot.per_ip_per_minute', 10).',1')
    ->name('chatbot.ask');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::resource('articles', AdminArticleController::class)->except('show');
    Route::resource('achievements', AdminAchievementController::class)->except('show');

    // EduRoute: pengelolaan kurikulum, guru, karier, prestasi, media, dan mitra.
    Route::prefix('eduroute')->name('eduroute.')->group(function () {
        Route::get('/', [EdurouteDashboardController::class, 'index'])->name('index');
        Route::get('/{program}', [EdurouteDashboardController::class, 'show'])->name('show');

        Route::resource('/{program}/curriculum', ProgramCurriculumController::class)->except(['show']);
        Route::resource('/{program}/teachers', ProgramTeacherController::class)->except(['show']);
        Route::resource('/{program}/careers', ProgramCareerController::class)->except(['show']);
        Route::resource('/{program}/achievements', ProgramAchievementController::class)->except(['show']);
        Route::resource('/{program}/media', ProgramMediaController::class)->except(['show']);
        Route::resource('/{program}/partners', ProgramPartnerController::class)->except(['show']);

        Route::get('/{program}/settings', [ProgramSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/{program}/settings', [ProgramSettingController::class, 'update'])->name('settings.update');
    });
});
