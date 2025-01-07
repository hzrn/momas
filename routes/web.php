<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\{
    CashflowController,
    CategoryInfoController,
    CategoryItemController,
    CommitteeController,
    HomeController,
    InfoController,
    ItemController,
    MosqueController,
    ProfileController,
    UserProfileController
};

// Import Middleware
use App\Http\Middleware\EnsureMosqueDataCompleted;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('auth.loginadminkit');
});

Route::get('lang/{locale}', function ($locale) {
    session(['app_locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');

Route::get('logout-user', function () {
    Auth::logout();
    return redirect('/');
})->name('logout-user');

// Authentication Routes
Auth::routes();

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Mosque Routes
    Route::resource('mosque', MosqueController::class);

    // Routes that require Mosque data to be completed
    Route::middleware(EnsureMosqueDataCompleted::class)->group(function () {

        // Home
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // Cashflow
        Route::prefix('cashflow')->name('cashflow.')->group(function () {
            Route::get('/export-pdf', [CashflowController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/analysis', [CashflowController::class, 'cashflowAnalysis'])->name('analysis');
            Route::get('/line-chart', [CashflowController::class, 'getLineChart'])->name('linechart');
            Route::get('/daily', [CashflowController::class, 'getDailyCashflow'])->name('getDailyCashflow');
            Route::get('/piechart', [CashflowController::class, 'getPieChart'])->name('piechart');
            Route::get('/export-excel', [CashflowController::class, 'exportExcel'])->name('exportExcel');
        });
        Route::resource('cashflow', CashflowController::class);

        // Info
        Route::prefix('info')->name('info.')->group(function () {
            Route::get('/calendar', [InfoController::class, 'calendarEvents'])->name('calendar');
            Route::get('/export-pdf', [InfoController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/analysis', [InfoController::class, 'infoAnalysis'])->name('analysis');
            Route::get('/piechart', [InfoController::class, 'fetchPieChartData'])->name('piechart');
            Route::get('/linechart', [InfoController::class, 'lineChart'])->name('linechart');
        });
        Route::post('/reminders/remove-all', [InfoController::class, 'removeAll'])->name('reminders.removeAll');
        Route::resource('info', InfoController::class);

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/export-pdf', [ProfileController::class, 'exportPDF'])->name('exportPDF');
        });
        Route::resource('profile', ProfileController::class);

        // User Profile
        Route::resource('userprofile', UserProfileController::class);

        // Category Info
        Route::resource('categoryinfo', CategoryInfoController::class);

        // Category Item
        Route::resource('categoryitem', CategoryItemController::class);

        // Item
        Route::prefix('item')->name('item.')->group(function () {
            Route::get('/export-pdf', [ItemController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/analysis', [ItemController::class, 'itemAnalysis'])->name('analysis');
            Route::get('/piechart', [ItemController::class, 'fetchPieChartData'])->name('piechart');
            Route::get('/linechart', [ItemController::class, 'lineChart'])->name('linechart');
        });
        Route::resource('item', ItemController::class);

        // Committee
        Route::prefix('committee')->name('committee.')->group(function () {
            Route::get('/export-pdf', [CommitteeController::class, 'exportPDF'])->name('exportPDF');
        });

        // Apply CacheResponse middleware to cache the index and show routes
        Route::middleware(CacheResponse::class)->group(function () {
            Route::get('/committee', [CommitteeController::class, 'index'])->name('committee.index');
            Route::get('/committee/{committee}', [CommitteeController::class, 'show'])->name('committee.show');
        });

        // Apply DoNotCacheResponse middleware to prevent caching for other committee routes
        Route::middleware(DoNotCacheResponse::class)->group(function () {
            Route::get('/committee/create', [CommitteeController::class, 'create'])->name('committee.create');
            Route::post('/committee', [CommitteeController::class, 'store'])->name('committee.store');
            Route::get('/committee/{committee}/edit', [CommitteeController::class, 'edit'])->name('committee.edit');
            Route::put('/committee/{committee}', [CommitteeController::class, 'update'])->name('committee.update');
            Route::delete('/committee/{committee}', [CommitteeController::class, 'destroy'])->name('committee.destroy');
        });
    });
});
