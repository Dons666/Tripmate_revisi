<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\TravelPlanController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\RecommendationDebugController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppealController;
use App\Http\Controllers\PenyediaTravelController;
use App\Http\Controllers\TravelDashboardController;
use App\Http\Controllers\TravelPortalController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/appeal', [AppealController::class, 'store'])->name('appeal.store');

// Direct Storage File Server Fallback for Hosting (cPanel / Linux Case-Sensitivity Fix)
Route::get('/storage/{path}', function ($path) {
    $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $path), '/');

    $possiblePaths = [
        storage_path('app/public/' . $cleanPath),
        storage_path('app/' . $cleanPath),
        public_path('storage/' . $cleanPath),
    ];

    foreach ($possiblePaths as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            return response()->file($filePath);
        }
    }

    // Case-insensitive fallback for Linux OS
    $dirName = dirname($cleanPath);
    $fileName = basename($cleanPath);
    $searchDir = storage_path('app/public/' . ($dirName !== '.' ? $dirName : ''));

    if (is_dir($searchDir)) {
        $files = scandir($searchDir);
        foreach ($files as $file) {
            if (strtolower($file) === strtolower($fileName)) {
                $filePath = $searchDir . '/' . $file;
                return response()->file($filePath);
            }
        }
    }

    abort(404);
})->where('path', '.*');

Route::get('/penyedia-travel', [PenyediaTravelController::class, 'index'])->name('penyedia-travel.index');
Route::get('/penyedia-travel/register', [PenyediaTravelController::class, 'create'])->name('penyedia-travel.create');
Route::post('/penyedia-travel/register', [PenyediaTravelController::class, 'store'])->name('penyedia-travel.store');
Route::get('/penyedia-travel/sukses', [PenyediaTravelController::class, 'success'])->name('penyedia-travel.success');
Route::get('/penyedia-travel/{travel}', [PenyediaTravelController::class, 'show'])->name('penyedia-travel.show');
Route::post('/penyedia-travel/{travel}/book', [App\Http\Controllers\TravelPlanController::class, 'bookPackage'])->name('travel.packages.book')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Travel Partner Dashboard & Packages
    Route::get('/travel/dashboard', [TravelDashboardController::class, 'index'])->name('travel.dashboard');
    Route::get('/travel/dashboard/edit', [TravelDashboardController::class, 'edit'])->name('travel.dashboard.edit');
    Route::post('/travel/dashboard/update', [TravelDashboardController::class, 'update'])->name('travel.dashboard.update');
    
    // Travel Packages Management
    Route::get('/travel/packages/create', [TravelDashboardController::class, 'createPackage'])->name('travel.packages.create');
    Route::post('/travel/packages', [TravelDashboardController::class, 'storePackage'])->name('travel.packages.store');
    Route::get('/travel/packages/{travel}/edit', [TravelDashboardController::class, 'editPackage'])->name('travel.packages.edit');
    Route::put('/travel/packages/{travel}', [TravelDashboardController::class, 'updatePackage'])->name('travel.packages.update');
    Route::delete('/travel/packages/{travel}', [TravelDashboardController::class, 'destroyPackage'])->name('travel.packages.destroy');

    // Armada Travel
    Route::resource('travel/armada', App\Http\Controllers\ArmadaController::class)->names('travel.armada')->except(['create', 'show', 'edit']);

    Route::get('/api/travel/{travel}/availability', [App\Http\Controllers\TravelPlanController::class, 'checkAvailability'])->name('api.travel.availability');

    Route::get('/search', [DestinasiController::class, 'search'])->name('destinasi.search');

    Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])->name('destinasi.show');

    Route::post('/destinasi/{id}/rate', [DestinasiController::class, 'storeRating'])->name('destinasi.rate');
    Route::post('/ratings/{type}/{id}', [RatingController::class, 'store'])->name('ratings.store');

    Route::get('/preference', [PreferenceController::class, 'create'])->name('preference.create');
    Route::post('/preference', [PreferenceController::class, 'store'])->name('preference.store');

    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');

    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/toggle', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    Route::resource('travel-plans', TravelPlanController::class)
        ->except(['create','edit','update']);

    Route::post('/travel-plans/{travelPlan}/add-destinasi',
        [TravelPlanController::class,'addDestinasi'])
        ->name('travel-plans.addDestinasi');

    Route::delete('/travel-plans/{travelPlan}/destinasi/{destinasi}',
        [TravelPlanController::class,'removeDestinasi'])
        ->name('travel-plans.removeDestinasi');

    Route::post('/travel-plans/{travelPlan}/expenses',
        [ExpenseController::class,'store'])
        ->name('expenses.store');

    Route::delete('/expenses/{expense}',
        [ExpenseController::class,'destroy'])
        ->name('expenses.destroy');

    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');
    
    // Rute Cerdas Dijkstra & Budget Planner
    Route::get('/rute-cerdas', [HomeController::class, 'dijkstra'])->name('rute.dijkstra');
    Route::get('/budget-planner', [App\Http\Controllers\BudgetController::class, 'index'])->name('budget.index');
    Route::post('/budget-planner/integrated-route', [App\Http\Controllers\BudgetController::class, 'integratedRoute'])->name('budget.integrated-route');
    Route::get(
    '/recommendation/debug',
        [RecommendationDebugController::class, 'index']
)->middleware('auth')
 ->name('recommendation.debug');

});

Route::middleware(['auth'])->group(function () {

    // Rencana Perjalanan
    Route::get('/travel-plans', [TravelPlanController::class, 'index'])->name('travel-plans.index');
    Route::post('/travel-plans', [TravelPlanController::class, 'store'])->name('travel-plans.store');
    Route::get('/travel-plans/{travelPlan}', [TravelPlanController::class, 'show'])->name('travel-plans.show');
    Route::post('/travel-plans/{travelPlan}/add-destinasi', [TravelPlanController::class, 'addDestinasi'])->name('travel-plans.addDestinasi');
    Route::post('/travel-plans/quick-add', [TravelPlanController::class, 'quickAdd'])->name('travel-plans.quick-add');
    Route::post('/travel-plans/save-integrated-route', [TravelPlanController::class, 'saveIntegratedRoute'])->name('travel-plans.save-integrated-route');
    Route::post('/travel-plans/{travelPlan}/complete', [TravelPlanController::class, 'complete'])->name('travel-plans.complete');
    Route::post('/travel-plans/{travelPlan}/attach-travel', [TravelPlanController::class, 'attachTravel'])->name('travel-plans.attach-travel');
    Route::get('/travel-plans/{travelPlan}/checkout', [TravelPlanController::class, 'checkout'])->name('travel-plans.checkout');
    Route::post('/travel-plans/{travelPlan}/checkout', [TravelPlanController::class, 'processCheckout'])->name('travel-plans.process-checkout');
    Route::get('/travel-plans/{travelPlan}/receipt', [TravelPlanController::class, 'receipt'])->name('travel-plans.receipt');
    Route::delete('/travel-plans/{travelPlan}/destinasi/{destinasi}', [TravelPlanController::class, 'removeDestinasi'])->name('travel-plans.removeDestinasi');
    Route::delete('/travel-plans/{travelPlan}', [TravelPlanController::class, 'destroy'])->name('travel-plans.destroy');

    // Jadwal Perjalanan (Schedules / Itinerary)
    Route::post('/travel-plans/{travelPlan}/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    // Admin routes
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('admin')
        ->group(function () {
            Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/places', [AdminController::class, 'placesIndex'])->name('places.index');

            Route::get('/destinations/create', [AdminController::class, 'createDestination'])->name('destinations.create');
            Route::post('/destinations', [AdminController::class, 'storeDestination'])->name('destinations.store');
            Route::get('/destinations/{destination}', [AdminController::class, 'showDestination'])->name('destinations.show');
            Route::get('/destinations/{destination}/edit', [AdminController::class, 'editDestination'])->name('destinations.edit');
            Route::put('/destinations/{destination}', [AdminController::class, 'updateDestination'])->name('destinations.update');
            Route::delete('/destinations/{destination}', [AdminController::class, 'destroyDestination'])->name('destinations.destroy');

            Route::get('/culinaries/create', [AdminController::class, 'createCulinary'])->name('culinaries.create');
            Route::post('/culinaries', [AdminController::class, 'storeCulinary'])->name('culinaries.store');
            Route::get('/culinaries/{culinary}', [AdminController::class, 'showCulinary'])->name('culinaries.show');
            Route::get('/culinaries/{culinary}/edit', [AdminController::class, 'editCulinary'])->name('culinaries.edit');
            Route::put('/culinaries/{culinary}', [AdminController::class, 'updateCulinary'])->name('culinaries.update');
            Route::delete('/culinaries/{culinary}', [AdminController::class, 'destroyCulinary'])->name('culinaries.destroy');

            Route::get('/stays/create', [AdminController::class, 'createStay'])->name('stays.create');
            Route::post('/stays', [AdminController::class, 'storeStay'])->name('stays.store');
            Route::get('/stays/{stay}', [AdminController::class, 'showStay'])->name('stays.show');
            Route::get('/stays/{stay}/edit', [AdminController::class, 'editStay'])->name('stays.edit');
            Route::put('/stays/{stay}', [AdminController::class, 'updateStay'])->name('stays.update');
            Route::delete('/stays/{stay}', [AdminController::class, 'destroyStay'])->name('stays.destroy');

            Route::get('/comments', [AdminController::class, 'commentsIndex'])->name('comments.index');
            Route::post('/comments/scan-ai', [AdminController::class, 'scanCommentsWithAi'])->name('comments.scan-ai');
            Route::post('/comments/{comment}/recheck-ai', [AdminController::class, 'recheckCommentWithAi'])->name('comments.recheck-ai');
            Route::delete('/comments/{comment}', [AdminController::class, 'destroyComment'])->name('comments.destroy');
            Route::post('/comments/{comment}/warning', [AdminController::class, 'sendWarning'])->name('comments.warning');

            // Escrow
            Route::get('/escrow', [AdminController::class, 'escrowDashboard'])->name('escrow.index');
            Route::post('/escrow/{travelPlan}/verify', [AdminController::class, 'verifyPayment'])->name('escrow.verify');
            Route::post('/escrow/{travelPlan}/payout', [AdminController::class, 'releasePayout'])->name('escrow.payout');
            Route::get('/escrow/{travelPlan}/proof', [AdminController::class, 'escrowProof'])->name('escrow.proof');

            Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::post('/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');
            Route::post('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.update-status');

            Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
            Route::get('/appeals', [AdminController::class, 'appealsIndex'])->name('appeals.index');
            Route::post('/appeals/{appeal}/approve', [AdminController::class, 'approveAppeal'])->name('appeals.approve');
            Route::post('/appeals/{appeal}/reject', [AdminController::class, 'rejectAppeal'])->name('appeals.reject');

            Route::get('/penyedia-travel', [AdminController::class, 'penyediaTravelIndex'])->name('penyedia-travel.index');
            Route::get('/penyedia-travel/create', [AdminController::class, 'penyediaTravelCreate'])->name('penyedia-travel.create');
            Route::post('/penyedia-travel', [AdminController::class, 'penyediaTravelStore'])->name('penyedia-travel.store');
            Route::post('/penyedia-travel/{penyediaTravel}/approve', [AdminController::class, 'penyediaTravelApprove'])->name('penyedia-travel.approve');
            Route::post('/penyedia-travel/{penyediaTravel}/reject', [AdminController::class, 'penyediaTravelReject'])->name('penyedia-travel.reject');
            Route::get('/penyedia-travel/{penyediaTravel}/document/{type}', [AdminController::class, 'penyediaTravelDocument'])->name('penyedia-travel.document');
            Route::get('/penyedia-travel/{penyediaTravel}/edit', [AdminController::class, 'penyediaTravelEdit'])->name('penyedia-travel.edit');
            Route::put('/penyedia-travel/{penyediaTravel}', [AdminController::class, 'penyediaTravelUpdate'])->name('penyedia-travel.update');
            Route::delete('/penyedia-travel/{penyediaTravel}', [AdminController::class, 'penyediaTravelDestroy'])->name('penyedia-travel.destroy');

            // Admin Escrow & Holding Funds
            Route::get('/escrow', [AdminController::class, 'escrowDashboard'])->name('escrow.index');
            Route::get('/escrow/{travelPlan}/proof', [AdminController::class, 'escrowProof'])->name('escrow.proof');
            Route::post('/escrow/{travelPlan}/verify', [AdminController::class, 'verifyPayment'])->name('escrow.verify');
            Route::post('/escrow/{travelPlan}/release', [AdminController::class, 'releasePayout'])->name('escrow.release');
        });

    // Portal Agen Travel (Role = travel / Admin)
    Route::post('/travel/start-trip/{travelPlan}', [TravelPortalController::class, 'startTrip'])->name('travel.portal.start-trip');
    Route::post('/travel/end-trip/{travelPlan}', [TravelPortalController::class, 'endTrip'])->name('travel.portal.end-trip');

    Route::post('/notifications/mark-all-read', [AdminController::class, 'markAllNotificationsRead'])
        ->name('notifications.mark-all-read');

});

require __DIR__.'/auth.php';