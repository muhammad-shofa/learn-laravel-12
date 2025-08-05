<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'dashboard']);

// article endpoint
Route::prefix('/api/article')->controller(ArtikelController::class)->group(function () {
    Route::get('/get-article', 'getAllArticleData');
    Route::post('/add-article', 'addArticle');
    Route::get('/get-article/{id}', 'getArticle');
    Route::put('/update-article/{id}', 'updateArticle');
    Route::delete('/delete-article/{id}', 'deleteArticle');
});
