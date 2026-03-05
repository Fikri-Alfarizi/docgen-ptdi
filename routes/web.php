<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SystemSettingsController;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect('/admin/dashboard')
            : redirect('/user/dashboard');
    }
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Dashboard Route (Public but Private by Hash)
Route::get('/share/dashboard/{token}', [AdminController::class, 'publicDashboard'])->name('dashboard.public');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/export', [AdminController::class, 'exportDashboardPdf'])->name('dashboard.export');
    Route::get('/search', [AdminController::class, 'globalSearch'])->name('search');

    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/templates', [AdminController::class, 'manageTemplates'])->name('templates');
    Route::post('/templates', [AdminController::class, 'storeTemplate'])->name('templates.store');
    Route::post('/templates/{id}', [AdminController::class, 'updateTemplate'])->name('templates.update');
    Route::delete('/templates/{id}', [AdminController::class, 'destroyTemplate'])->name('templates.destroy');
    Route::get('/documents/history', [AdminController::class, 'historyDocuments'])->name('documents.history');
    Route::delete('/documents/{id}', [AdminController::class, 'destroyDocument'])->name('documents.destroy');
    Route::get('/documents/export', [AdminController::class, 'exportDocumentsPdf'])->name('documents.export');

    Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SystemSettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/toggle', [SystemSettingsController::class, 'ajaxToggleVisibility'])->name('settings.toggle');
});

// User/Employee Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/documents', [UserController::class, 'myDocuments'])->name('documents');
    Route::get('/documents/upload', [UserController::class, 'upload'])->name('documents.upload');
    Route::post('/documents', [DocumentController::class, 'storeUpload'])->name('documents.store');
    Route::post('/documents/{id}/update', [DocumentController::class, 'updateUpload'])->name('documents.update');
    Route::post('/documents/generate', [DocumentController::class, 'generate'])->name('documents.generate');
});

// Shared routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{id}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
});
