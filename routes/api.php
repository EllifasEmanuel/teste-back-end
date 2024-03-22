<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::prefix('/product')->group(function () {
        Route::post('', [ProductController::class, 'store'])->name('products.store');
        Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{id}', [ProductController::class, 'delete'])->name('products.delete');
        Route::get('/category-name', [ProductController::class, 'searchByNameAndCategory'])->name('products.search_by_category_and_name');
        Route::get('/with-image', [ProductController::class, 'searchWithImage'])->name('products.search_with_image');
        Route::get('/without-image', [ProductController::class, 'searchWithoutImage'])->name('products.search_without_image');
        Route::get('/category', [ProductController::class, 'searchByCategory'])->name('products.search_by_category');
        Route::get('/{id}', [ProductController::class, 'show'])->name('products.show');
});

?>
