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
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::middleware('guest.custom')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    // Route::get('/register', [RegisterController::class, 'index']);
    // Route::post('/register', [RegisterController::class, 'store']);
});


Route::prefix('admin')  ->group(function () {

    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard')->middleware('check.user.id');

    Route::controller(UserController::class)->middleware('check.user.id')->group(function(){
        Route::get('users/index','index');
        Route::post('users/store','store');
        Route::get('users/edit/{id}','edit');
        Route::put('users/update/{id}','update');
        Route::delete('users/{id}/delete','destroy');
    });
    // ->middleware('check.user.id')
    Route::controller(BranchController::class)->middleware('check.user.id')->group(function(){
        Route::get('branches/index','index');
        Route::post('branches/store','store');
        Route::get('branches/edit/{id}','edit');
        Route::put('branches/update/{id}','update');
        Route::delete('branches/{id}/delete','destroy');
    });



    Route::controller(TherapistController::class)->middleware('check.user.id')->group(function(){
        Route::get('therapists/index','index');
        Route::post('therapists/store','store');
        Route::get('therapists/edit/{id}','edit');
        Route::put('therapists/update/{id}','update');
        Route::delete('therapists/{id}/delete','destroy');
    });


    Route::controller(CustomerController::class)->group(function(){
        Route::get('customers/index','index');
        Route::post('customers/store','store');
        Route::get('customers/{id}','edit')->middleware('check.user.id');
        Route::put('customers/update/{id}','update')->middleware('check.user.id');
        Route::delete('customers/{id}/destroy','destroy')->middleware('check.user.id');
        Route::get('customer/export','export')->middleware('check.user.id');
        Route::post('customer/import', 'import')->middleware('check.user.id');
    });


    Route::controller(RoleController::class)->middleware('check.user.id')->group(function(){
        Route::get('roles/index','index');
        Route::post('roles/store','store');
        Route::get('roles/{id}/edit','edit');
        Route::put('roles/update/{role}','update');
        Route::delete('roles/{role}/delete','destroy');

    });


    Route::controller(ExpenseController::class)->group(function(){
        Route::get('expenses/index','index');
        Route::post('expenses/store','store');
        Route::get('expenses/edit/{id}','edit');
        Route::put('expenses/update/{expense}','update');
        Route::delete('expenses/{expense}/delete','destroy');
    });

    Route::controller(PermissionController::class)->middleware('check.user.id')->group(function(){
        Route::get('permissions/index','index');
        Route::post('permissions/store','store');
        Route::get('permissions/{id}','edit');
        Route::put('permissions/update/{permission}', 'update');
        Route::delete('permissions/{permission}/destroy','destroy');
    });

    Route::controller(TherapyController::class)->middleware('check.user.id')->group(function(){
        Route::get('therapies/index','index');
        Route::post('therapies/store','store');
        Route::get('therapies/{id}/edit','edit');
        Route::put('therapies/update/{therapy}','update');
        Route::delete('therapies/{therapy}/delete','destroy');
    });

    Route::controller(PackageController::class)->group(function(){
        Route::get('packages/index','index');
        Route::post('packages/store','store');
        Route::get('packages/{id}/edit','edit')->middleware('check.user.id');
        Route::put('packages/update/{service}','update')->middleware('check.user.id');
        Route::delete('packages/{service}/delete','destroy')->middleware('check.user.id');

    });


    Route::controller(ReceiptController::class)->group(function(){
        Route::get('receipts/index','index');
        Route::get('receipts/get-phone', 'getPhone');
        Route::get('/receipts/package-therapies/{packageId}', 'getPackageTherapies')->name('receipts.package-therapies');
        Route::get('receipts/{id}/download', 'downloadReceipt')->middleware('check.user.id');
        Route::post('receipts/store','store');
        Route::get('receipts/search-customers', 'searchCustomers');
        Route::get('receipts/{id}/edit', 'edit')->middleware('check.user.id');
        Route::put('receipts/{id}','update')->middleware('check.user.id');
        Route::delete('receipts/{id}/delete','destroy')->middleware('check.user.id');
        Route::get('receipts/show/{id}','show');
        Route::get('receipts/{id}/download','downloadPdf')->middleware('check.user.id');
    });

    Route::controller(TelecallerController::class)->group(function(){
    Route::get('telecaller/index','index');
    Route::post('telecaller/store','store')->middleware('check.user.id');
    Route::post('telecaller/import', 'import')->middleware('check.user.id');

    });


    Route::controller(ReportController::class)->middleware('check.user.id')->group(function(){
        Route::get('reports/sales-summary','sales_summaryReport');
        Route::get('reports/customer','customerReport');
        Route::get('reports/packages','packagesReport');
        Route::get('reports/appointments','appointmentsReport');
        Route::get('reports/branch-report','branchReport');

    });

    // Route::controller(PackageUsageController::class)->group(function(){
    //     Route::get('package-usage/index','index');
    //     Route::get('package-usage/show/{id}',  'show');
    //     Route::post('package-usage/update/{id}',  'update');

    // });

    Route::controller(CustomerUsageController::class)->group(function (){

        Route::get('usage/packages','packagesUsage');
        Route::get('usage/therapies','therapiesUsage')->middleware('check.user.id');
        Route::post('usage/updateTherapyUsage','updateTherapyUsage')->middleware('check.user.id');
        Route::post('usage/updatePackageUsage','updatePackageUsage');

        Route::get('receipt/{id}/details','getReceiptDetails')->name('admin.receipt.details');
        Route::get('usage/therapies/{receipt}','getReceiptTherapies') ->name('admin.usage.therapies.get')->middleware('check.user.id');


    });

    Route::controller(SavingsController::class)->group(function(){
        Route::get('savings/index','index');
        Route::post('savings/store','store');
        Route::get('savings/{id}/edit','edit')->middleware('check.user.id');
        Route::put('savings/update/{id}','update')->middleware('check.user.id');
        Route::delete('savings/{id}/delete','destroy')->middleware('check.user.id');
    });

});


