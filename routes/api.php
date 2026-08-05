<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinasiController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\TravelPlanController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\RouteController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\PreferenceController;
use App\Http\Controllers\Api\TravelController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/recommendation/itinerary', [RecommendationController::class, 'generateItinerary']);

// Auth
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Destinasi (read-only public)
Route::get('destinasi',      [DestinasiController::class, 'index']);
Route::get('destinasi/{id}', [DestinasiController::class, 'show']);

// Algoritma budget (public)
Route::post('budget-recommendation', [BudgetController::class, 'recommend']);
Route::post('integrated-route',      [BudgetController::class, 'getIntegratedRoute']);

// Algoritma Dijkstra / Nearest-Neighbor (public)
Route::get('dijkstra/{start}/{end}', [RouteController::class, 'show']);

// Rating destinasi (list rating — publik)
Route::get('ratings/destinasi/{id}', [RatingController::class, 'index']);

// Health check
Route::get('test', fn () => response()->json(['message' => 'API TripMate OK']));

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);
    Route::post('auth/profile', [AuthController::class, 'updateProfile']);

    // Rekomendasi TF-IDF (personalized)
    Route::get('recommendations', [RecommendationController::class, 'index']);

    // Bookmarks
    Route::get('bookmarks',         [BookmarkController::class, 'index']);
    Route::post('bookmarks/toggle', [BookmarkController::class, 'toggle']);
    Route::delete('bookmarks/{id}', [BookmarkController::class, 'destroy']);

    // Travel Plans
    Route::get('travel-plans',              [TravelPlanController::class, 'index']);
    Route::post('travel-plans',             [TravelPlanController::class, 'store']);
    Route::get('travel-plans/{id}',         [TravelPlanController::class, 'show']);
    Route::put('travel-plans/{id}',         [TravelPlanController::class, 'update']);
    Route::delete('travel-plans/{id}',      [TravelPlanController::class, 'destroy']);
    Route::post('travel-plans/{id}/complete', [TravelPlanController::class, 'complete']);
    Route::post(
        'travel-plans/{id}/destinasi',
        [TravelPlanController::class, 'addDestinasi']
    );
    Route::delete(
        'travel-plans/{planId}/destinasi/{destinasiId}',
        [TravelPlanController::class, 'removeDestinasi']
    );
    Route::post('travel-plans/{planId}/destinasi/{destinasiId}/toggle-visited', [TravelPlanController::class, 'toggleVisited']);

    // Travels (List & Detail)
    Route::get('travels',                  [TravelController::class, 'index']);
    Route::get('travels/{id}',             [TravelController::class, 'show']);
    Route::post('travel-plans/book-package', [TravelPlanController::class, 'bookPackage']);
    Route::post('travel-plans/{id}/attach-travel', [TravelPlanController::class, 'attachTravel']);
    Route::post('travel-plans/{id}/checkout-travel', [TravelPlanController::class, 'checkoutTravel']);

    // Expenses
    Route::get('expenses',          [ExpenseController::class, 'index']);
    Route::post('expenses',         [ExpenseController::class, 'store']);
    Route::delete('expenses/{id}',  [ExpenseController::class, 'destroy']);

    // Simpan rute budget ke Travel Plan
    Route::post('save-trip-plan', [BudgetController::class, 'saveToPlan']);

    // Schedules
    Route::get('travel-plans/{planId}/schedules',          [ScheduleController::class, 'index']);
    Route::post('travel-plans/{planId}/schedules',         [ScheduleController::class, 'store']);
    Route::put('travel-plans/{planId}/schedules/{id}',     [ScheduleController::class, 'update']);
    Route::delete('travel-plans/{planId}/schedules/{id}',  [ScheduleController::class, 'destroy']);

    // Rating & Review (submit/update — auth)
    Route::post('ratings/destinasi/{id}', [RatingController::class, 'store']);
    Route::post('ratings/travel/{id}',    [RatingController::class, 'storeTravel']);
    Route::get('ratings/my',              [RatingController::class, 'my']);

    // Preference user
    Route::get('preferences',  [PreferenceController::class, 'show']);
    Route::post('preferences', [PreferenceController::class, 'store']);

});