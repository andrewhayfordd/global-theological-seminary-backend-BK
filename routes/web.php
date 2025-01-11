<?php

use App\Http\Controllers\Routecontroller;

use App\Http\Controllers\Staff\StaffRouteController;
use App\Http\Controllers\Gradecontroller;

use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|confirmSave
*/

Route::middleware(['auth'])->group(function(){
    Route::get('supplier',[SupplierController::class,'showPage']);
});
Route::get('/', [Routecontroller::class, 'dashboard'])->name('dashboard');

Route::get('/student', [Routecontroller::class, 'student'])->name('student');
Route::get("student/transcript/download/{code}",[Routecontroller::class,"dwTranscript"])->where('code', '.*');;
Route::get('req', [RouteController::class,"req"])->name('req');
Route::get('/batch', [Routecontroller::class, 'batch'])->name('batch');
Route::get('/department', [Routecontroller::class, 'department'])->name('department');
Route::get('/staff', [Routecontroller::class, 'staff'])->name('staff');
Route::get('/applications', [Routecontroller::class, 'applications'])->name('applications');
//Route::get('/assignment', [Routecontroller::class, 'assignment'])->name('assignment');
Route::get('/transcript', [Routecontroller::class, 'transcript'])->name('transcript');
Route::get('/library', [Routecontroller::class, 'library'])->name('library');
Route::get('/bill', [Routecontroller::class, 'bill'])->name('bill');
Route::get('/payment', [Routecontroller::class, 'payment'])->name('payment');
Route::get('/courses', [Routecontroller::class, 'admincourse']);
Route::get('expenditure',[Routecontroller::class,'expenditure']);
Route::get('/program', [Routecontroller::class, 'program'])->name('program');
Route::get('/messaging', [Routecontroller::class, 'messaging'])->name('messaging');
Route::get('/manageuser', [Routecontroller::class, 'manageUser'])->name('manageuser');
Route::get('/library', [Routecontroller::class, 'library'])->name('library');
Route::get("/payment", [Routecontroller::class, 'payment'])->name('payment');
Route::get('/services', [Routecontroller::class, 'services'])->name('services');
Route::get('/inventory', [Routecontroller::class, 'inventory'])->name('inventory');
Route::get('/grade/{num}',[Gradecontroller::class,'grade'])->name('grade');

Route::prefix('lecturer')->group(function () {
    Route::get('/students', [StaffRouteController::class, 'student'])->name('student');
    Route::get('/message', [StaffRouteController::class, 'messaging'])->name('message');
    Route::get('/notice', [StaffRouteController::class, 'notice'])->name('notice');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect("/");
    });
});

Route::prefix('students')->group(function () {
    Route::post('/', [StudentsController::class, 'store']);
});


Route::prefix('lecturer')->group(function () {
    Route::get('/students', [StaffRouteController::class, 'student'])->name('student');
    Route::get('/message', [StaffRouteController::class, 'messaging'])->name('message');
    Route::get('/notice', [StaffRouteController::class, 'notice'])->name('notice');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect("/");
    });
});

Route::prefix('students')->group(function () {
    Route::post('/', [StudentsController::class, 'store']);
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect("/");
});
Route::get('/forgot_password', function () {
    return view("auth.forgot_password");
});
require __DIR__ . '/auth.php';