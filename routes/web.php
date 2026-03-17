<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GrupActivityController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TypeMeetingController;
use App\Http\Controllers\PicController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';

Route::middleware(['auth'])->group(function () {
    Route::get('type-meetings/code', [TypeMeetingController::class, 'code']);
    Route::get('categories/code', [CategoryController::class, 'code']);
    Route::get('topic/code', [TopicController::class, 'code']);

    Route::resource('categories', CategoryController::class);
    Route::resource('type-meetings', TypeMeetingController::class);
    Route::resource('topic', TopicController::class);
    Route::resource('place', PlaceController::class);
    Route::resource('grup-activity', GrupActivityController::class);
    Route::resource('activity', ActivityController::class);
    Route::resource('pic', PicController::class);
});
