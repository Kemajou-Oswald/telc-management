<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankDepositController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/centers', [CenterController::class, 'index'])->name('centers.index');
    Route::get('/centers/create', [CenterController::class, 'create'])->name('centers.create');
    Route::post('/centers', [CenterController::class, 'store'])->name('centers.store');
    Route::get('/centers/{center}/edit', [CenterController::class, 'edit'])->name('centers.edit');
    Route::put('/centers/{center}', [CenterController::class, 'update'])->name('centers.update'); // corrigé
    Route::get('/centers/{center}/confirm-archive', [CenterController::class, 'confirmArchive'])->name('centers.confirm-archive');
    Route::post('/centers/{center}/archive', [CenterController::class, 'archive'])->name('centers.archive');
    Route::post('/center/switch', [CenterController::class, 'switchCenter'])->name('center.switch');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{managedUser}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{managedUser}', [UserManagementController::class, 'update'])->name('users.update'); // corrigé
    Route::post('/users/{managedUser}/toggle-block', [UserManagementController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::post('/users/{managedUser}/archive', [UserManagementController::class, 'archive'])->name('users.archive');

    Route::get('/activity-logs', [AuditLogController::class, 'index'])->name('activity.index');

    Route::get('/sessions', [ExamSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [ExamSessionController::class, 'create'])->name('sessions.create');
    Route::get('/sessions/{session}', [ExamSessionController::class, 'show'])->name('sessions.show');
    Route::get('/sessions/{session}/edit', [ExamSessionController::class, 'edit'])->name('sessions.edit');
    Route::post('/sessions', [ExamSessionController::class, 'store'])->name('sessions.store');
    Route::put('/sessions/{session}', [ExamSessionController::class, 'update'])->name('sessions.update'); // corrigé
    Route::post('/sessions/{session}/toggle', [ExamSessionController::class, 'toggle'])->name('sessions.toggle');
    Route::post('/sessions/{session}/archive', [ExamSessionController::class, 'archive'])->name('sessions.archive');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/export', [RegistrationController::class, 'export'])->name('registrations.export');
    Route::get('/registrations/export/download', [RegistrationController::class, 'downloadExport'])->name('registrations.export.download');
    Route::get('/registrations/export/pdf', [RegistrationController::class, 'downloadExportPdf'])->name('registrations.export.pdf');
    Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/{registration}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit');
    Route::put('/registrations/{registration}', [RegistrationController::class, 'update'])->name('registrations.update'); // corrigé
    Route::post('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
    Route::post('/registrations/{registration}/pay', [RegistrationController::class, 'pay'])->name('registrations.pay');
    Route::get('/registrations/{registration}/receipt', [RegistrationController::class, 'receipt'])->name('registrations.receipt');
    Route::get('/registrations/{registration}/receipt/pdf', [RegistrationController::class, 'downloadReceipt'])->name('registrations.receipt.download');

    Route::get('/deposits', [BankDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [BankDepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [BankDepositController::class, 'store'])->name('deposits.store');
    Route::post('/deposits/{deposit}/review', [BankDepositController::class, 'review'])->name('deposits.review');
    Route::get('/deposits/{deposit}/slip', [BankDepositController::class, 'previewSlip'])->name('deposits.slip.preview');
    Route::get('/deposits/{deposit}/slip/download', [BankDepositController::class, 'downloadSlip'])->name('deposits.slip.download');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

