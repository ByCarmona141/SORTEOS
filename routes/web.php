<?php

use App\Http\Controllers\Web\RaffleController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestMailController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\PrizeController;
use App\Http\Controllers\TestReportController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('test-mail')->group(function () {
    Route::get('/blade', [TestMailController::class, 'blade']);
    Route::get('/markdown', [TestMailController::class, 'markdown']);
    Route::get('/simple', [TestMailController::class, 'simple']);
    Route::get('/with-attachment', [TestMailController::class, 'withAttachment']);
    Route::get('/with-pdf', [TestMailController::class, 'withPdf']);
});

Route::prefix('test-pdf')->group(function () {
    Route::get('/generate', [TestReportController::class, 'generatePdf']);
});

// Rutas públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Rutas protegidas (requieren estar logueado)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resources([
        'user' => UserController::class,
        'raffle' => RaffleController::class,
        'role' => RoleController::class,
        'payment' => PaymentController::class,
    ]);

    Route::resource('raffle.prize', PrizeController::class)->except('show');
    Route::get('raffle/{raffle}/prize/{prize}/winner', [PrizeController::class, 'editWinner'])->name('raffle.prize.winner.edit');
    Route::put('raffle/{raffle}/prize/{prize}/winner', [PrizeController::class, 'updateWinner'])->name('raffle.prize.winner.update');
    Route::get('/raffle/{raffle}/tickets', [TicketController::class, 'index'])->name('raffle.tickets.index');
    Route::post('/raffle/{raffle}/tickets/generate', [TicketController::class, 'generate'])->name('raffle.tickets.generate');

    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payment.approve');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payment.reject');
    Route::post('/payments/{payment}/revert', [PaymentController::class, 'revertToPending'])->name('payment.revert');
});
