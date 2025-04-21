<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Resume\ResumeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resume routes
    Route::get('/profile/resume', [ResumeController::class, 'create'])->name('resume.create');
    Route::post('/profile/resume', [ResumeController::class, 'store'])->name('resume.store');

    Route::prefix('resume')->group(function () {

        Route::get('/', function (){
            return Inertia::render('Resume/Resume');
        })->name('resume');

        Route::get('/create', function (){
            return Inertia::render('Resume/CreateResume');
        })->name('vue.resume.create');

        Route::post('/', [ResumeController::class, 'store'])->name('vue.resume.store');


    });
});

require __DIR__.'/auth.php';
