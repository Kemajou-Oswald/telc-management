<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\QuoteRequestController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES (Public)
|--------------------------------------------------------------------------
*/

// Accueil
Route::get('/', [FrontController::class, 'index'])->name('home');

// Catalogue général
Route::get('/catalogue', [FrontController::class, 'catalogue'])->name('catalogue');

// Fiche produit
Route::get('/produit/{slug}', [FrontController::class, 'productShow'])->name('product.show');

// Solutions
Route::get('/solutions', [FrontController::class, 'solutions'])->name('solutions');

// Pages statiques (ATTENTION : vues à la racine)
Route::view('/a-propos', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Demande de devis
Route::post('/devis', [QuoteController::class, 'store'])->name('quote.store');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

// Redirection dashboard Breeze vers admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Protégées)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_active'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Profil
        Route::get('profile', [AdminUserController::class, 'profile'])
            ->name('profile');

        Route::put('profile', [AdminUserController::class, 'updateProfile'])
            ->name('profile.update');

        // Utilisateurs
        Route::resource('users', AdminUserController::class);

        // Catégories
        Route::resource('categories', CategoryController::class);

        // Sous-catégories
        Route::resource('subcategories', SubcategoryController::class);

        // Produits
        Route::resource('products', ProductController::class);

        // Devis
        Route::resource('quotes', QuoteRequestController::class)
            ->only([
                'index',
                'show',
                'update',
                'destroy'
            ]);
    });
