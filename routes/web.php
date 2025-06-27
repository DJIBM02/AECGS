<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes pour les événements
Route::prefix('events')->name('events.')->group(function () {
    // Routes publiques
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');

    // Routes pour la gestion des événements (futurs rôles admin/président)

    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');

    // Actions spéciales
    Route::patch('/{event}/toggle-featured', [EventController::class, 'toggleFeatured'])->name('toggle-featured');
});

// API Routes pour future interface admin
Route::prefix('api/events')->name('api.events.')->group(function () {
    Route::get('/', [EventController::class, 'apiIndex'])->name('index');
});


// Route pour la page de contact (référencée dans plusieurs vues)
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::view('/services', 'services')->name('services');
Route::view('/membership', 'membership')->name('membership');

//Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/list-detail', [HomeController::class, 'listDetail'])->name('list_detail');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
