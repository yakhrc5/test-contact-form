<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;




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

/*
|--------------------------------------------------------------------------
| Contact（お問い合わせ）
|--------------------------------------------------------------------------
*/

Route::get('/', [ContactController::class, 'index']);
  Route::post('/contacts/confirm', [ContactController::class, 'confirm']);
  Route::post('/contacts', [ContactController::class, 'store'])->name('contact.store');
/*
|--------------------------------------------------------------------------
| Admin（管理画面）
|--------------------------------------------------------------------------
*/

  Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'admin']);
    Route::get('/search', [AdminController::class, 'admin']);
    Route::delete('/delete', [AdminController::class, 'destroy']);
    Route::get('/export', [AdminController::class, 'export']);
    Route::get('/reset', [AdminController::class, 'reset']);
  });
  Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
/*
|--------------------------------------------------------------------------
| auth（登録、ログイン）
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginStore']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerStore']);
