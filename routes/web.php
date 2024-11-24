<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/register', 'register');

// Users will be redirected to this route if not logged in
Volt::route('/login', 'login')->name('login');

// Define the logout
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});

// Protected routes here
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Volt::route('/users', 'users.index');
    Volt::route('/users/create', 'users.create');
    Volt::route('/users/{user}/edit', 'users.edit');
    // ... more
});

Route::middleware('auth')->group(function () {
    Volt::route('/', 'index');

    Volt::route('/products', 'products.index');
    Volt::route('/products/create', 'products.create');
    Volt::route('/products/{product}/edit', 'products.edit');

    Volt::route('/transactions', 'transactions.index');
    Volt::route('/transactions/create', 'transactions.create');
    Volt::route('/transactions/{transaction}/edit', 'transactions.edit');
});
