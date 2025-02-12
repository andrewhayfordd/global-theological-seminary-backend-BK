<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\Routecontroller;

use App\Http\Controllers\Staff\StaffRouteController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\StudentHomeworkController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Api\HomeworkApiController;
use App\Http\Controllers\Api\StudentHomeworkApiController;
use App\Models\Homework;
use App\Models\Student;
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
Route::get('/student/{student_no}/grades', [GradeController::class, 'index'])->name('student.grades');


Route::prefix('students')->group(function () {
    Route::post('/', [StudentsController::class, 'store']);
});


// Route::prefix('lecturer')->group(function () {
//     Route::get('/students', [StaffRouteController::class, 'student'])->name('student');
//     Route::get('/message', [StaffRouteController::class, 'messaging'])->name('message');
//     Route::get('/notice', [StaffRouteController::class, 'notice'])->name('notice');

//     Route::get('/homework', [HomeworkController::class, 'index'])->name('homework.index');
//     Route::post('/homework/upload', [HomeworkController::class, 'uploadHomework'])->name('homework.upload');
//     Route::get('/homework/download/{id}', [HomeworkController::class, 'downloadHomework'])->name('homework.download');
//     Route::post('/homework/submit/{id}', [HomeworkController::class, 'submitHomework'])->name('homework.submit');
//     Route::get('/logout', function () {
//         Auth::logout();
//         return redirect("/");
//     });
// });

Route::prefix('lecturer')->middleware('auth')->group(function () {
    Route::get('/students', [StaffRouteController::class, 'student'])->name('student');
    Route::get('/message', [StaffRouteController::class, 'messaging'])->name('message');
    Route::get('/notice', [StaffRouteController::class, 'notice'])->name('notice');
    Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect("/");
    });
});


Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    
    Route::get('/notices', [NoticeController::class, 'index'])->name('notice.index');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notice.store');
    Route::get('/notices/{id}/edit', [NoticeController::class, 'edit'])->name('notice.edit');
    Route::put('/notices/{id}', [NoticeController::class, 'update'])->name('notice.update');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notice.delete');
    
});

Route::prefix('timetables')->name('timetables.')->group(function () {
    Route::get('/', [TimetableController::class, 'index'])->name('index');
    Route::get('/timetables/create', [TimetableController::class, 'create'])->name('timetables.create');
    Route::post('/', [TimetableController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TimetableController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TimetableController::class, 'update'])->name('update');
    Route::delete('/{id}', [TimetableController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/studenthomeworks', [StudentHomeworkController::class, 'index'])->name('studenthomeworks.index');
    Route::post('/studenthomeworks/store', [StudentHomeworkController::class, 'store'])->name('studenthomeworks.store');

    Route::get('/homeworks', [HomeworkController::class, 'index'])->name('homework.index');
    Route::post('/homeworks', [HomeworkController::class, 'store'])->name('homework.store');
    Route::get('/homeworks/{id}/edit', [HomeworkController::class, 'edit'])->name('homework.edit');
    Route::put('/homeworks/{id}', [HomeworkController::class, 'update'])->name('homework.update');
    Route::delete('/homeworks/{id}', [HomeworkController::class, 'delete'])->name('homework.delete');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/get-students', [AttendanceController::class, 'getStudents'])->name('attendance.getStudents');
});


Route::get('/logout', function () {
    Auth::logout();
    return redirect("/");
});

Route::get('/forgot_password', function () {
    return view("auth.forgot_password");
});
require __DIR__ . '/auth.php';