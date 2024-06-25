<?php

use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\UsersController;
use App\Models\DocumentCategory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


// Home
Route::get('/', function () {
    $categories = DocumentCategory::get()->sortBy('category_name');

    return view('welcome', compact('categories'));
});
// Route::get('/register', function () { return view('welcome'); });

// Auth Route
Auth::routes();

Route::get('search',[DocumentController::class, 'find2'])->name('web.find2');
Route::get('dts',[DocumentController::class, 'dts'])->name('dts');

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('user')->group(function () {
        Route::get('profile', [UsersController::class, 'profile'])->name('user.profile');
        Route::patch('updateProfile', [UsersController::class, 'updateProfile'])->name('user.updateProfile');
    });

    // Document
    Route::prefix('document')->group(function () {
        Route::get('/all', [DocumentController::class, 'create'])->name('document.create');
        Route::get('/completed', [DocumentController::class, 'completed'])->name('document.completed');
        Route::get('/received', [DocumentController::class, 'received'])->name('document.received');
        Route::get('/incoming', [DocumentController::class, 'incoming'])->name('document.incoming');
        Route::get('/outgoing', [DocumentController::class, 'outgoing'])->name('document.outgoing');
        Route::get('/received/history', [DocumentController::class, 'receivedHistory'])->name('document.receivedHistory');
        Route::get('/tracked', [DocumentController::class, 'tracked'])->name('document.tracked');
        Route::get('/search',[DocumentController::class, 'find'])->name('web.find');
        Route::patch('/update/{id}', [DocumentController::class, 'update'])->name('document.update');
        Route::patch('/update-edit/{id}', [DocumentController::class, 'updateEdit'])->name('document.updateEdit');
        Route::delete('/destroy/{id}', [DocumentController::class, 'destroy'])->name('document.destroy');
        Route::get('/getDocument/{id}', [DocumentController::class, 'getDocument'])->name('document.getDocument');
        Route::get('/dm', [DocumentController::class, 'decision_maker'])->name('document.decision_maker');
        Route::get('/generateCode', [DocumentController::class, 'generateCode'])->name('document.generateCode');
        Route::patch('/undoActionComplete/{id}', [DocumentController::class, 'undoActionComplete'])->name('document.undoActionComplete');
        Route::delete('/changeForward/{id}', [DocumentController::class, 'changeForward'])->name('document.changeForward');

        Route::get('system-updates', [HomeController::class, 'systemUpdates'])->name('document.systemUpdates');

        Route::resources([ 'document_category' => DocumentCategoryController::class ]);
        Route::patch('storeGuestCreate/{id}', [DocumentController::class, 'storeGuestCreate'])->name('document.storeGuestCreate');
        Route::delete('deleteGuestCode/{id}', [DocumentController::class, 'deleteGuestCode'])->name('document.deleteGuestCode');

        // Ajax
        Route::get('/dtAllDocuments', [DocumentController::class, 'dtAllDocuments'])->name('document.dtAllDocuments');
        Route::get('/dtIncoming', [DocumentController::class, 'dtIncoming'])->name('document.dtIncoming');
    });

    // Admin
    Route::prefix('admin')->group(function () {
        Route::resources([
            'office' => OfficeController::class,
            'users' => UsersController::class,
            'terminals' => TerminalController::class
        ]);

        Route::get('/getTerminals/{q}', [TerminalController::class, 'getTerminals'])->name('terminals.getTerminals');
    });



    Route::post('document', [DocumentController::class, 'store'])->name('document.store');
});

// For registration only
Route::post('/guestStore', [UsersController::class, 'guestStore'])->name('guestStore');
Route::post('/guestCreate', [DocumentController::class, 'guestCreate'])->name('guest.guestCreate');

Route::get('/printPDF/{id}',[DocumentController::class, 'printPDF'])->name('printPDF');





