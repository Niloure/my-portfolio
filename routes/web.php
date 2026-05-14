<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Project;
use App\Http\Controllers\BlogController;
use App\Models\Post;

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

// --- PUBLIC ROUTES (Bisa diakses siapa saja) ---
Route::get('/', [ProfileController::class, 'index']);
Route::get('/portfolio', [ProfileController::class, 'portfolio'])->name('projects.index');

// Route Blog Publik
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');


// --- PROTECTED ROUTES (Harus Login) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'posts' => App\Models\Post::latest()->get(),
            'projects' => App\Models\Project::all(),
            'stacks' => App\Models\TechStack::all(),
            'socials' => App\Models\Setting::all(),
        ]);
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Management Blog (Store, Update, Delete)
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::patch('/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');

    // Management Projects, Stacks, & Socials
    Route::post('/projects', [ProfileController::class, 'store'])->name('projects.store');
    Route::patch('/projects/{project}', [ProfileController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProfileController::class, 'destroy'])->name('projects.destroy');
    
    Route::post('/stacks', [ProfileController::class, 'storeStack'])->name('stacks.store');
    Route::delete('/stacks/{stack}', [ProfileController::class, 'destroyStack'])->name('stacks.destroy');

    Route::post('/socials', [ProfileController::class, 'storeSocial'])->name('socials.store');
    Route::delete('/socials/{social}', [ProfileController::class, 'destroySocial'])->name('socials.destroy');
});

require __DIR__.'/auth.php';