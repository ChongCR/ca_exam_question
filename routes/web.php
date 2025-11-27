<?php

use App\Http\Controllers\FundController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/instructions', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/exam', function () {
    return view('exam.index');
})->name('exam.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get("investors/create", [InvestorController::class, 'create'])->name('investor.create');
    Route::post("investors", [InvestorController::class, 'store'])->name('investor.store');
    Route::get("investors/{investor}/edit", [InvestorController::class, 'edit'])->name('investor.edit');
    Route::put("investors/{investor}", [InvestorController::class, 'update'])->name('investor.update');
    Route::get("investors", [InvestorController::class, 'index'])->name('investor.index');
    Route::get("funds", [FundController::class, 'index'])->name('funds.index');
    Route::get("investments", [InvestmentController::class, 'index'])->name('investments.index');
    Route::get("graph", [GraphController::class, 'index'])->name('graph.index');
});

require __DIR__ . '/auth.php';
