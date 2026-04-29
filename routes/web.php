<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\Auth\AdminLoginController;

/* --- Public Routes --- */
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::fallback(function() {
    return response()->view('errors.404', [], 404);
});

Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::post('/refunds/store', [BillingController::class, 'storeRefund'])->name('refunds.store');

/* --- Authenticated Routes (All Roles) --- */
Route::middleware(['auth:admin'])->group(function () {

    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    /* 1. SHARED AREA: Super Admin, Admin, and Staff */
    Route::resource('members', MemberController::class);
    Route::post('/members/{id}/status', [MemberController::class, 'updateStatus'])->name('members.updateStatus');
    
    Route::resource('equipment', EquipmentController::class);
    Route::resource('billing', BillingController::class);
    Route::post('/billing/renew/{id}', [BillingController::class, 'renew'])->name('billing.renew');
    Route::resource('staff', StaffController::class);

    /* 2. RESTRICTED AREA: Super Admin and Admin ONLY */
    Route::middleware(['can:access-admin-only,admin'])->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // FIX: Changed 'admin-staff' to 'adminstaff' to match JS form.action = `/adminstaff/${id}`
        // Resource automatically handles GET, POST, PUT (update), and DELETE
        Route::resource('adminstaff', AdminStaffController::class);
        
        // Admin-only Refund Actions
        Route::post('/refunds/{id}/approve', [BillingController::class, 'approveRefund'])->name('refunds.approve');
        Route::post('/refunds/{id}/decline', [BillingController::class, 'declineRefund'])->name('refunds.decline');
        Route::post('/refunds/disburse', [BillingController::class, 'storeDisbursement'])->name('refunds.disburse');
    });
});