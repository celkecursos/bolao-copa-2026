<?php

use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\BetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Palpites do usuário
    Route::get('/palpites', [BetController::class, 'index'])->name('bets.index');
    Route::post('/palpites/{game}', [BetController::class, 'store'])
        ->middleware('permission:bets.create')
        ->name('bets.store');

    // Área administrativa
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('teams', TeamController::class)
            ->except('show')
            ->middleware('permission:teams.manage');

        Route::resource('games', GameController::class)
            ->except('show')
            ->middleware('permission:games.manage');

        Route::get('games/{game}/resultado', [GameController::class, 'resultForm'])
            ->middleware('permission:games.set-result')
            ->name('games.result');
        Route::put('games/{game}/resultado', [GameController::class, 'storeResult'])
            ->middleware('permission:games.set-result')
            ->name('games.result.store');

        Route::get('usuarios', [UserRoleController::class, 'index'])
            ->middleware('role:super-admin')
            ->name('users.index');
        Route::put('usuarios/{user}', [UserRoleController::class, 'update'])
            ->middleware('role:super-admin')
            ->name('users.update');
    });
});

require __DIR__.'/auth.php';
