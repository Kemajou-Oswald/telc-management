<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    UserController,
    StudentController,
    StudentPaymentController,
    SupportRequestController,
    StockController,
    GroupController,
    TeacherAttendanceController,
    StudentAttendanceController,
    CashBookController,
    ProfileController,
    TuitionFeeController,
    DashboardSuperAdminController,
    DashboardDirecteurController,
    DashboardSecretaireController,
    DashboardProfesseurController
};

/*
|--------------------------------------------------------------------------
| ACCUEIL ET AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // Redirection automatique vers le bon dashboard selon le rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARDS SPÉCIFIQUES
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | DASHBOARDS SPÉCIFIQUES
    |--------------------------------------------------------------------------
    */
    // ... (garder les imports en haut)


        // DASHBOARDS PAR RÔLE
        Route::get('/dashboard/super-admin', [DashboardSuperAdminController::class, 'index'])->middleware('role:super_admin')->name('dashboards.super_admin');
        Route::get('/dashboard/directeur', [DashboardDirecteurController::class, 'index'])->middleware('role:directeur')->name('dashboards.directeur');
        Route::get('/dashboard/secretaire', [DashboardSecretaireController::class, 'index'])->middleware('role:secretaire')->name('dashboards.secretaire');
        Route::get('/dashboard/professeur', [DashboardProfesseurController::class, 'index'])->middleware('role:professeur')->name('dashboards.professeur');

        // ... (garder le reste de tes routes étudiants, groupes, etc. sans changement)
    
    /*
    |--------------------------------------------------------------------------
    | GESTION DES ÉTUDIANTS (Workflow Intelligent)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin,directeur,secretaire,professeur'])->group(function () {

        // 1. Exportation & Insolvables
        Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
        Route::get('/students-insolvent', [StudentController::class, 'insolvent'])->name('students.insolvent');

        // 2. Transfert et Promotion (Règles métier 1, 2, 3, 4)
        Route::get('/students/{student}/transfer', [StudentController::class, 'showTransferForm'])->name('students.transfer.form');
        Route::post('/students/{student}/transfer', [StudentController::class, 'transfer'])->name('students.transfer');

        // 3. Finances et Reçus PDF
        Route::get('/students/{student}/payments', [StudentPaymentController::class, 'show'])->name('students.payments.show');
        Route::post('/students/{student}/payments', [StudentPaymentController::class, 'store'])->name('students.payments.store');
        Route::get('/payments/{payment}/receipt', [StudentPaymentController::class, 'downloadReceipt'])->name('payments.receipt');

        // 4. Ressources CRUD standard
        Route::resource('students', StudentController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | GESTION DES GROUPES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin,directeur,secretaire'])->group(function () {
        Route::get('/groups/{group}/export', [GroupController::class, 'exportGroup'])->name('groups.export');
        Route::resource('groups', GroupController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PRÉSENCES & POINTAGE
    |--------------------------------------------------------------------------
    */
    // Professeurs
    Route::post('/teacher/checkin/{group}', [TeacherAttendanceController::class, 'checkin'])->middleware('role:professeur')->name('teacher.checkin');
    Route::get('/teacher/stats', [TeacherAttendanceController::class, 'stats'])->name('teacher.stats');

    Route::middleware(['role:secretaire,super_admin,directeur'])->group(function () {
        Route::get('/teacher/validation', [TeacherAttendanceController::class, 'validationList'])->name('teacher.validation');
        Route::post('/teacher/validate/{attendance}', [TeacherAttendanceController::class, 'validateAttendance'])->name('teacher.validate');
    });

    // Étudiants (Appel)
    Route::middleware(['role:professeur,super_admin,directeur'])->group(function () {
        Route::get('/student-attendances', [StudentAttendanceController::class, 'index'])->name('student_attendances.index');
        Route::get('/student-attendances/{group}', [StudentAttendanceController::class, 'create'])->name('student_attendances.create');
        Route::post('/student-attendances/{group}', [StudentAttendanceController::class, 'store'])->name('student_attendances.store');
        Route::get('/student-attendances/{group}/history', [StudentAttendanceController::class, 'history'])->name('student_attendances.history');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATION & LOGISTIQUE
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin,directeur'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('tuition-fees', TuitionFeeController::class)->names('tuition_fees')->only(['index', 'create', 'store']);
    });

    Route::middleware(['role:super_admin,directeur,secretaire'])->group(function () {
        Route::get('/support-requests', [SupportRequestController::class, 'index'])->name('support_requests.index');
        Route::post('/support-requests', [SupportRequestController::class, 'store'])->name('support_requests.store');
        Route::post('/support-requests/{supportRequest}/resolve', [SupportRequestController::class, 'resolve'])->name('support_requests.resolve');
    });

    // Stock
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::post('/products', [StockController::class, 'storeProduct'])->name('products.store');
        Route::post('/products/{product}/movements', [StockController::class, 'storeMovement'])->name('movements.store');
    });

    // Caisse du jour
    Route::prefix('cash')->name('cash.')->group(function () {
        Route::get('/', [CashBookController::class, 'index'])->name('index');
        Route::post('/open-today', [CashBookController::class, 'openToday'])->name('open_today');
        Route::get('/{cashBook}', [CashBookController::class, 'show'])->name('show');
        Route::post('/{cashBook}/transactions', [CashBookController::class, 'storeTransaction'])->name('transactions.store');
        Route::post('/{cashBook}/close', [CashBookController::class, 'close'])->name('close');
        Route::post('/transactions/{transaction}/cancel', [CashBookController::class, 'cancelTransaction'])->name('transactions.cancel');
    });
});

require __DIR__ . '/auth.php';
