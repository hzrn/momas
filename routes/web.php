<?php

use App\Http\Controllers\CashflowController;
use App\Http\Controllers\CategoryInfoController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureMosqueDataCompleted;
use App\Http\Controllers\UserProfileController;
use App\Models\Info;


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

// Language switching route
Route::get('lang/{locale}', function ($locale) {
    session(['app_locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');

Route::get('logout-user', function () {
    Auth::logout();
    return redirect('/');
})->name('logout-user');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::resource('mosque', MosqueController::class);

     Route::middleware(EnsureMosqueDataCompleted::class)->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/cashflow/export-pdf', [CashflowController::class, 'exportPDF'])->name('cashflow.exportPDF');
        Route::resource('cashflow', CashflowController::class);
        Route::get('/info/export-pdf', [InfoController::class, 'exportPDF'])->name('info.exportPDF');
        Route::resource('info', InfoController::class);
        Route::resource('userprofile', UserProfileController::class);
        Route::get('/profile/export-pdf', [ProfileController::class, 'exportPDF'])->name('profile.exportPDF');
        Route::resource('profile', ProfileController::class);
        Route::resource('categoryinfo', CategoryInfoController::class);
        Route::resource('categoryitem', CategoryItemController::class);
        Route::get('/item/export-pdf', [ItemController::class, 'exportPDF'])->name('item.exportPDF');
        Route::resource('item', ItemController::class);
        Route::get('/committee/export-pdf', [CommitteeController::class, 'exportPDF'])->name('committee.exportPDF');
        Route::resource('committee', CommitteeController::class);




    });
});
