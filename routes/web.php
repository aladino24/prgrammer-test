<?php

use App\Http\Controllers\LearningActivityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LearningActivityController::class, 'index']);
Route::get('/metode', [LearningActivityController::class, 'get_learning_method'])->name('get-learning-method');
Route::get('/metode/{id}/edit', [LearningActivityController::class, 'get_learning_method_edit'])->name('get_learning_method_edit');
Route::get('/get-category-methods', [LearningActivityController::class, 'getCategoryMethods'])->name('get-category-methods');
Route::put('/metode/{id}', [LearningActivityController::class, 'edit_learning_method'])->name('get_learning_method');
Route::delete('/metode/{id}', [LearningActivityController::class, 'delete_learning_method'])->name('delete_learning_method');
Route::get('/get-learning-method-datatable', [LearningActivityController::class, 'get_learning_method_datatable'])->name('get-learning-method-datatable');
Route::post('/metode', [LearningActivityController::class, 'store_learning_method'])->name('store-learning-method');
Route::post('/store-activity', [LearningActivityController::class, 'store'])->name('store-activity');