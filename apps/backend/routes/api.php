<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;

/**
 * Health check
 */
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now(),
    ]);
});

/**
 * Routes protégées par Sanctum (authentification requise)
 */
Route::middleware('auth:sanctum')->group(function () {
    
    // Route utilisateur actuel
    Route::get('/auth/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });

    // Listings routes - CRUD complet
    Route::prefix('listings')->controller(ListingController::class)->group(function () {
        Route::post('/', 'store');                    // POST   /api/listings
        Route::patch('{listing}', 'update');          // PATCH  /api/listings/{id}
        Route::delete('{listing}', 'destroy');        // DELETE /api/listings/{id}
        Route::post('{listing}/publish', 'publish');  // POST   /api/listings/{id}/publish
        Route::post('{listing}/mark-sold', 'markSold'); // POST /api/listings/{id}/mark-sold
        Route::post('{listing}/archive', 'archive');  // POST   /api/listings/{id}/archive
    });
});

/**
 * Routes publiques (sans authentification)
 */
Route::prefix('listings')->controller(ListingController::class)->group(function () {
    Route::get('/', 'index');           // GET  /api/listings
    Route::get('{listing}', 'show');    // GET  /api/listings/{id}
});

/**
 * Placeholder pour les routes futures
 */
// Route::prefix('reviews')->controller(ReviewController::class)->group(function () {
//     Route::get('/', 'index');
//     Route::post('/', 'store')->middleware('auth:sanctum');
// });

// Route::prefix('repair-services')->controller(RepairServiceController::class)->group(function () {
//     Route::get('/', 'index');
//     Route::post('/', 'store')->middleware('auth:sanctum');
// });