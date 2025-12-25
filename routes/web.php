<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TodoController;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Todo/Index');
    })->name('dashboard');
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/datatable', [TodoController::class, 'datatableview'])->name('todos.datatable');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::resource('todos', TodoController::class);
});
 



require __DIR__.'/settings.php';
