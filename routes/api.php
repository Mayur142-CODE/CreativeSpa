<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TherapistController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TherapyController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TelecallerController;
use App\Http\Controllers\Admin\PackageUsageController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomerUsageController;
use App\Http\Controllers\Admin\SavingsController;

use App\Http\Controllers\Auth\LoginController;

/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "api" middleware group.
|----------------------------------------------------------------------
*/

// Unauthenticated Routes (Guest)
Route::middleware('guest.custom.api')->group(function () {
    Route::get('/', [LoginController::class, 'index2'])->name('login');
    Route::get('/login', [LoginController::class, 'index2'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate2']);
});

// Authenticated Routes
Route::prefix('admin')->middleware('auth.custom.api')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout2']);

    Route::get('/dashboard', [DashboardController::class, 'index2'])->name('dashboard')->middleware('check.user.id.api');

    Route::controller(UserController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('users/index','index2');
        Route::post('users/store','store2');
        Route::get('users/edit/{id}','edit2');
        Route::put('users/update/{id}','update2');
        Route::delete('users/{id}/delete','destroy2');
    });

    Route::controller(BranchController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('branches/index','index2');
        Route::post('branches/store','store2');
        Route::get('branches/edit/{id}','edit2');
        Route::put('branches/update/{id}','update2');
        Route::delete('branches/{id}/delete','destroy2');
    });

    Route::controller(TherapistController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('therapists/index','index2');
        Route::post('therapists/store','store2');
        Route::get('therapists/edit/{id}','edit2');
        Route::put('therapists/update/{id}','update2');
        Route::delete('therapists/{id}/delete','destroy2');
    });

    Route::controller(CustomerController::class)->group(function(){
        Route::get('customers/index','index2');
        Route::post('customers/store','store2');
        Route::get('customers/{id}','edit2')->middleware('check.user.id.api');
        Route::put('customers/update/{id}','update2')->middleware('check.user.id.api');
        Route::delete('customers/{id}/destroy','destroy2')->middleware('check.user.id.api');
        Route::get('customer/export','export2')->middleware('check.user.id.api');
        Route::post('customer/import', 'import2')->middleware('check.user.id.api');
    });

    Route::controller(RoleController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('roles/index','index2');
        Route::post('roles/store','store2');
        Route::get('roles/{id}/edit','edit2');
        Route::put('roles/update/{role}','update2');
        Route::delete('roles/{role}/delete','destroy2');
    });

    Route::controller(ExpenseController::class)->group(function(){
        Route::get('expenses/index','index2');
        Route::post('expenses/store','store2');
        Route::get('expenses/edit/{id}','edit2');
        Route::put('expenses/update/{expense}','update2');
        Route::delete('expenses/{expense}/delete','destroy2');
    });

    Route::controller(PermissionController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('permissions/index','index2');
        Route::post('permissions/store','store2');
        Route::get('permissions/{id}','edit2');
        Route::put('permissions/update/{permission}', 'update2');
        Route::delete('permissions/{permission}/destroy','destroy2');
    });

    Route::controller(TherapyController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('therapies/index','index2');
        Route::post('therapies/store','store2');
        Route::get('therapies/{id}/edit','edit2');
        Route::put('therapies/update/{therapy}','update2');
        Route::delete('therapies/{therapy}/delete','destroy2');
    });

    Route::controller(PackageController::class)->group(function(){
        Route::get('packages/index','index2');
        Route::post('packages/store','store2');
        Route::get('packages/{id}/edit','edit2')->middleware('check.user.id.api');
        Route::put('packages/update/{service}','update2')->middleware('check.user.id.api');
        Route::delete('packages/{service}/delete','destroy2')->middleware('check.user.id.api');
    });

    Route::controller(ReceiptController::class)->group(function(){
        Route::get('receipts/index','index2');
        Route::get('receipts/get-phone', 'getPhone2');
        Route::get('/receipts/package-therapies/{packageId}', 'getPackageTherapies2')->name('receipts.package-therapies');
        Route::get('receipts/{id}/download', 'downloadReceipt2')->middleware('check.user.id.api');
        Route::post('receipts/store','store2');
        Route::get('receipts/search-customers', 'searchCustomers2');
        Route::get('receipts/{id}/edit', 'edit2')->middleware('check.user.id.api');
        Route::put('receipts/{id}','update2')->middleware('check.user.id.api');
        Route::delete('receipts/{id}/delete','destroy2')->middleware('check.user.id.api');
        Route::get('receipts/show/{id}','show2')->middleware('check.user.id.api');
        Route::get('receipts/{id}/download','downloadPdf2')->middleware('check.user.id.api');
    });

    Route::controller(TelecallerController::class)->group(function(){
        Route::get('telecaller/index','index2');
        Route::post('telecaller/store','store2')->middleware('check.user.id.api');
        Route::post('telecaller/import', 'import2')->middleware('check.user.id.api');
    });

    Route::controller(ReportController::class)->middleware('check.user.id.api')->group(function(){
        Route::get('reports/sales-summary','sales_summaryReport2');
        Route::get('reports/customer','customerReport2');
        Route::get('reports/packages','packagesReport2');
        Route::get('reports/appointments','appointmentsReport2');
        Route::get('reports/branch-report','branchReport2');
    });

    Route::controller(CustomerUsageController::class)->group(function (){
        Route::get('usage/packages','packagesUsage2');
        Route::get('usage/therapies','therapiesUsage2')->middleware('check.user.id.api');
        Route::post('usage/updateTherapyUsage','updateTherapyUsage2')->middleware('check.user.id.api');
        Route::post('usage/updatePackageUsage','updatePackageUsage2');
        Route::get('receipt/{id}/details','getReceiptDetails2')->name('admin.receipt.details');
        Route::get('usage/therapies/{receipt}','getReceiptTherapies2') ->name('admin.usage.therapies.get')->middleware('check.user.id.api');
    });

    Route::controller(SavingsController::class)->group(function(){
        Route::get('savings/index','index2');
        Route::post('savings/store','store2');
        Route::get('savings/{id}/edit','edit2')->middleware('check.user.id.api');
        Route::put('savings/update/{id}','update2')->middleware('check.user.id.api');
        Route::delete('savings/{id}/delete','destroy2')->middleware('check.user.id.api');
    });
});
