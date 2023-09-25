<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::resource('categories', CategoryController::class);
Route::resource('brands', BrandController::class);
Route::resource('products', ProductController::class);
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);

Route::get('categories/images/{filename}', function ($filename) {
    $path = 'public/images/' . $filename; // Adjust the path to match your storage configuration
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::mimeType($path);

    return response($file)->header('Content-Type', $mimeType);
})->name('category.images');

Route::get('brands/images/{filename}', function ($filename) {
    $path = 'public/images/' . $filename; // Adjust the path to match your storage configuration
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::mimeType($path);

    return response($file)->header('Content-Type', $mimeType);
})->name('brand.images');