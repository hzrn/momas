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
        Route::resource('cashflow', CashflowController::class);
        Route::resource('info', InfoController::class);
        Route::resource('userprofile', UserProfileController::class);
        Route::resource('profile', ProfileController::class);
        Route::resource('categoryinfo', CategoryInfoController::class);
        Route::resource('categoryitem', CategoryItemController::class);
        Route::resource('item', ItemController::class);
        Route::resource('committee', CommitteeController::class);

        
    });
});
