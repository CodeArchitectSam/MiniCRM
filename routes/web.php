<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/contact');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/contact/{id}', [ContactController::class, 'show'])->name('contact.show');
    Route::put('/contact/{id}', [ContactController::class, 'update'])->name('contact.update');
    Route::delete('/contact/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
    Route::post('/contact/filter', [ContactController::class, 'filter'])->name('contact.filter');
    Route::get('/contact/merge/get-contacts/{id}', [ContactController::class, 'getContactsForMerge'])->name('contact.merge.get-contacts');
    Route::post('/contact/merge', [ContactController::class, 'merge'])->name('contact.merge');

    Route::get('/custom-field', [CustomFieldController::class, 'index'])->name('custom-field');
    Route::post('/custom-field', [CustomFieldController::class, 'store'])->name('custom-field.store');
    Route::get('/custom-field/list', [CustomFieldController::class, 'list'])->name('custom-field.list');
    Route::get('/custom-field/{id}', [CustomFieldController::class, 'show'])->name('custom-field.show');
    Route::put('/custom-field/{id}', [CustomFieldController::class, 'update'])->name('custom-field.update');

    Route::get('/file/serve', [FileController::class, 'serveFile'])->name('file.serve');
});

require __DIR__.'/auth.php';