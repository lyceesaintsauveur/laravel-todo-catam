<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ListeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodosController;
use Illuminate\Support\Facades\Route;

// Activation du middleware d'authentification pour toutes les routes suivantes
Route::middleware('auth')->group(function () {
    // Action correspondant au form de la vue (donc POST) et appel de la fonction saveTodo du controller
    // Route concernant les todos
    // Route::get('/', [TodosController::class, 'liste'])->name('todo.liste'); //feat/scopeByStatus
    // feature : #issue#2 : filtre de la liste des todos (toutes, en cours, terminées)

    Route::get('/{filtre?}', [TodosController::class, 'liste'])
        ->name('todo.liste')
        ->where('filtre', 'toutes|en_cours|terminees');

    Route::post('/action/add', [TodosController::class, 'saveTodo'])->name('todo.save');
    Route::get('/action/done/{id}', [TodosController::class, 'markAsDone'])->name('todo.done');
    // Route::get('/action/delete/{id}', [TodosController::class, 'deleteTodo'])->name('todo.delete'); #Issue#1 : suppression directe
    Route::delete('/action/delete/{id}', [TodosController::class, 'deleteTodo'])->name('todo.delete');
    Route::get('/action/lower/{id}', [TodosController::class,  'lowerPriority'])->name('todo.lower');
    Route::get('/action/raise/{id}', [TodosController::class, 'raisePriority'])->name('todo.raise');

    // Route concernant les pages
    Route::get('/compteur', [TodosController::class, 'viewCompteur'])->name('todo.compteur');
    Route::get('/about', [TodosController::class, 'viewAPropos'])->name('todo.about');

    // Routes pour la recherche de todos
    // Utilise POST côté accueil, mais redirige en GET vers la page résultats
    Route::get('/todos/recherche', [TodosController::class, 'search'])
        ->name('todos.search');                 // page résultats (GET)
    Route::post('/todos/search', [TodosController::class, 'searchSubmit'])
        ->name('todos.search.submit');          // soumission du formulaire (POST)

    // Routes concernant les listes
    Route::get('/liste', [ListeController::class, 'viewListe'])->name('liste.liste')->middleware(['auth', 'verified']);
    Route::controller(ListeController::class)->group(function () {
        Route::post('/action/addL', 'saveListe')->name('liste.save');
        Route::get('/action/deleteL/{id}', 'deleteListe')->name('liste.delete');
    })->middleware(['auth', 'verified']);

    // Route du planning des todos
    Route::get('/planning', [TodosController::class, 'planning'])->name('todo.planning')->middleware(['auth', 'verified']);

});

// Groupe de routes avec middleware d'authentification
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

});

// //Route::get('login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
// Route::middleware('throttle:10,1')->group(function () {
//     Route::get('/login', [AuthenticatedSessionController::class, 'create'])
//         ->middleware('guest')
//         ->name('login');
// });

require __DIR__ . '/auth.php';
