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

/*
|--------------------------------------------------------------------------|
| Web Routes                                                               |
|--------------------------------------------------------------------------|
| Here is where you can register web routes for your application. These    |
| routes are loaded by the RouteServiceProvider and all of them will       |
| be assigned to the "web" middleware group. Make something great!          |
|--------------------------------------------------------------------------|
*/

// Public Routes
Route::get('/', function () {
    return view('auth.loginadminkit');
});

// Apply caching to language switch and logout, as these are relatively static
Route::get('lang/{locale}', function ($locale) {
    session(['app_locale' => $locale]);
    return redirect()->back();
})->name('lang.switch')->middleware('cacheResponse'); // Caching this route

Route::get('logout-user', function () {
    Auth::logout();
    return redirect('/');
})->name('logout-user')->middleware('cacheResponse'); // Caching this route

// Authentication Routes
Auth::routes();

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Mosque Routes
    Route::resource('mosque', MosqueController::class);

    // Routes that require Mosque data to be completed
    Route::middleware(EnsureMosqueDataCompleted::class)->group(function () {

        // Home - Caching home route as it shows main dashboard information
        Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('cacheResponse');

        // Cashflow Routes - Caching dashboard-related routes
        Route::prefix('cashflow')->name('cashflow.')->group(function () {
            Route::get('/export-pdf', [CashflowController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/analysis', [CashflowController::class, 'cashflowAnalysis'])->name('analysis');
            Route::get('/line-chart', [CashflowController::class, 'getLineChart'])->name('linechart');
            Route::get('/daily', [CashflowController::class, 'getDailyCashflow'])->name('getDailyCashflow');
            Route::get('/piechart', [CashflowController::class, 'getPieChart'])->name('piechart');
        });
        Route::resource('cashflow', CashflowController::class)->middleware('cacheResponse'); // Caching cashflow-related routes

        // Info Routes - Caching info-related routes like reminders and reports
        Route::prefix('info')->name('info.')->group(function () {
            Route::get('/calendar', [InfoController::class, 'calendarEvents'])->name('calendar')->middleware('cacheResponse'); // Caching this route
            Route::get('/export-pdf', [InfoController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/analysis', [InfoController::class, 'infoAnalysis'])->name('analysis');
            Route::get('/piechart', [InfoController::class, 'fetchPieChartData'])->name('piechart');
            Route::get('/linechart', [InfoController::class, 'lineChart'])->name('linechart');
        });
        Route::post('/reminders/remove-all', [InfoController::class, 'removeAll'])->name('reminders.removeAll');
        Route::resource('info', InfoController::class)->middleware('cacheResponse'); // Caching info-related routes

        // Profile Routes - Caching profile routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/export-pdf', [ProfileController::class, 'exportPDF'])->name('exportPDF');
        });
        Route::resource('profile', ProfileController::class)->middleware('cacheResponse'); // Caching profile routes

        // User Profile Routes
        Route::resource('userprofile', UserProfileController::class);

        // Category Info Routes
        Route::resource('categoryinfo', CategoryInfoController::class);

        // Category Item Routes
        Route::resource('categoryitem', CategoryItemController::class);

        // Item Routes - Caching item-related routes
        Route::prefix('item')->name('item.')->group(function () {
            Route::get('/export-pdf', [ItemController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/analysis', [ItemController::class, 'itemAnalysis'])->name('analysis');
            Route::get('/piechart', [ItemController::class, 'fetchPieChartData'])->name('piechart');
            Route::get('/linechart', [ItemController::class, 'lineChart'])->name('linechart');
        });
        Route::resource('item', ItemController::class)->middleware('cacheResponse'); // Caching item routes

        // Committee Routes - Caching committee routes
        Route::prefix('committee')->name('committee.')->group(function () {
            Route::get('/export-pdf', [CommitteeController::class, 'exportPDF'])->name('exportPDF');
        });
        Route::resource('committee', CommitteeController::class)->middleware('cacheResponse'); // Caching committee routes
    });
});
