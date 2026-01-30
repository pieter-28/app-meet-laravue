<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TypeMeetingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TopicController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';

Route::middleware(['auth'])->group(function () {
    Route::get('type-meetings/code', [TypeMeetingController::class, 'code']);
    Route::get('categories/code', [CategoryController::class, 'code']);
    Route::get('topic/code', [TopicController::class, 'code']);

    Route::resource('categories', CategoryController::class);
    Route::resource('type-meetings', TypeMeetingController::class);
    Route::resource('topic', TopicController::class);
});
