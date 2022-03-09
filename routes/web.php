<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Controller;

Route::prefix('/admin')->name('admin.')->middleware('admin')->group(function() {

    Route::get('/home', [MainController::class, 'index'])->name('home');
    Route::get('/stats', [StatsController::class, 'index'])->name('stats');
    Route::get('/stats-user', [StatsController::class, 'perUser'])->name('stats.user')->middleware('admin.super');
    Route::get('/stats-user-week', [StatsController::class, 'userWeek'])->name('stats.user.week');
    Route::post('/stats-user', [StatsController::class, 'searchPerUser'])->name('stats.user.search')->middleware('admin.super');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings')->middleware('admin.super');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('admin.super');
    Route::resource('users', UserController::class)->middleware('admin.super');
    Route::resource('domains', DomainController::class)->middleware('admin.super');
    Route::resource('links', LinkController::class);
});

Route::get('/postback', [Controller::class, 'postBack'])->name('post-back');
Route::get('/go/{domain}/{link}', [LinkController::class, 'go'])->name('link-go');

Route::name('user.')->group(function(){

    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }
        return view('login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login'])->name('login-form');

    Route::get('/logout', function() {
        Auth::logout();
        return redirect(route('user.login'));
    })->name('logout');

    Route::get('/registration', function() {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }
        return view('registration');
    })->name('registration');

    Route::post('/registration', [RegisterController::class, 'save'])->name('registration-form');
});
