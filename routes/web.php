<?php

use Illuminate\Support\Facades\{Route, Auth};
use App\Http\Controllers\{
    HomeController, UserController, RolePermissionController
};

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::view('dashboard', 'dashboard')->name('dashboard');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('role-permission', RolePermissionController::class, ['except' => ['create','edit']]);
Route::resource('user', UserController::class, ['except' => ['create','edit']]);

// Set Super Admin
Route::get('set-super-admin', [UserController::class, 'setSuperAdmin']);