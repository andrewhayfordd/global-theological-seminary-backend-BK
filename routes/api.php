<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MobileStaffController;
use App\Http\Controllers\API\MobileStudentController;

use App\Http\Controllers\API\NoticeController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\departmentcontroller;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\managecoursescontroller;

use App\Http\Controllers\messagecontroller;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DashbaordController;
use App\Http\Controllers\ExpenditureController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryStoreController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\registercontroller;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\Staff\StaffMessageController;
use App\Http\Controllers\Staff\StaffNoticeController;
use App\Http\Controllers\Staff\StaffStudentController;

use App\Http\Controllers\staffcontroller;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierMemberController;
use App\Http\Controllers\UserManagementController;
use App\Http\Resources\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Route for batch
Route::prefix('batch')->group(function () {
    Route::get('/{school_code}', [BatchController::class, 'index']);
    Route::post('add', [BatchController::class, 'store']);
    Route::post('delete/{batchCode}', [BatchController::class, 'destroy']);
    Route::post('update', [BatchController::class, 'update']);
    Route::get('batch_list/{school_code}/{batchCode}', [BatchController::class, 'fetchStudent']);
});

//Application route
Route::prefix('application')->group(function () {
    Route::get('/{schoolCode}', [ApplicationController::class, 'index']);
    Route::post('/delete/{id}', [ApplicationController::class, 'destroy']);
    Route::post('/edit', [StudentsController::class, 'update']);
    Route::get('{schoolCode}/student_stats', [StudentsController::class, 'studentStats']);
});

//Student route
Route::prefix('student')->group(function () {
    Route::get('/{schoolcode}', [StudentsController::class, 'index']);
    Route::get('/{schoolcode}/inactive', [StudentsController::class, 'inactive']);
    Route::post('/edit', [StudentsController::class, 'update']);
    Route::post('/delete/{id}', [StudentsController::class, 'destroy']);
    Route::post('/restore/{id}', [StudentsController::class, 'restore']);
    Route::get('{schoolCode}/student_stats', [StudentsController::class, 'studentStats']);
    Route::get("/filterstudent/{studentdata}", [StudentsController::class, 'filterStudent']);
});

//Route for assessment
Route::prefix('assessment')->group(function () {
    Route::post('/filter_fetch_terminal_report/{schoolCode}', [AssessmentController::class, 'filterFetchTerminalReport']);
    Route::get("/all/{code}", [AssessmentController::class, 'all']);
    Route::post('/store', [AssessmentController::class, 'store']);
    Route::post("delete/{code}", [AssessmentController::class, 'delete']);
    Route::post("update", [AssessmentController::class, "update"]);
});

//Route for department
Route::prefix('department')->group(function () {
    Route::get('/{school_code}', [departmentcontroller::class, 'index']);
    Route::get('department_list/{school_code}/{deptCode}', [departmentcontroller::class, 'fetchDepartmentList']);
    Route::post('add', [departmentcontroller::class, 'store']);
    Route::post('delete/{departmentcode}', [departmentcontroller::class, 'destroy']);
    Route::post('update', [departmentcontroller::class, 'update']);
});

//Route for satff module
Route::prefix('staff')->group(function () {
    Route::get('/{school_code}', [staffcontroller::class, 'index']);
    Route::post('add', [staffcontroller::class, 'store']);
    Route::post('edit', [StaffController::class, 'update']);
    Route::post('add_contact', [staffcontroller::class, 'addContact']);
    Route::post('add_qual', [staffcontroller::class, 'addQual']);
    Route::post('add_emp', [staffcontroller::class, 'addEmployment']);
    Route::post('add_acc', [staffcontroller::class, 'addAccount']);
    Route::post('delete/{staffno}', [staffcontroller::class, 'destroy']);
    Route::post('contact_delete/{staffno}', [staffcontroller::class, 'contactDelete']);
    Route::post('qual_delete/{staffno}', [staffcontroller::class, 'qualDelete']);
    Route::post('emp_delete/{staffno}', [staffcontroller::class, 'empDelete']);
    Route::post('acc_delete/{staffno}', [staffcontroller::class, 'accDelete']);
    Route::get('fetch_qual/{school_code}', [staffcontroller::class, 'fetchStaffQual']);
    Route::get('fetch_contact/{school_code}', [staffcontroller::class, 'fetchStaffContact']);
    Route::get('fetch_employment/{school_code}', [staffcontroller::class, 'fetchStaffEmployment']);
    Route::get('fetch_account/{school_code}', [staffcontroller::class, 'fetchStaffAccountDetails']);
});

//Route for program module
Route::prefix('program')->group(function () {
    Route::get('/{school_code}', [ProgrammesController::class, 'index']);
    Route::post('add', [ProgrammesController::class, 'store']);
    Route::post('update', [ProgrammesController::class, 'update']);
    Route::post('delete/{transid}', [ProgrammesController::class, 'destroy']);
    Route::get('program_list/{school_code}/{progCode}', [ProgrammesController::class, 'fetchProgramList']);
    Route::post('/selected/{selectedElement}/{school_code}', [ProgrammesController::class, 'selectedprogram']);
});

//Route for courses management
Route::prefix('course')->group(function () {
    Route::post('add', [CourseController::class, "store"]);
    Route::post('/update', [CourseController::class, 'update']);
    Route::post('/update_assigned_courses', [CourseController::class, 'updateAssignedCourses']);
    Route::post('/delete/{coursecode}/{schoool_code}', [CourseController::class, "destroy"]);
    Route::get('/{schoool_code}', [CourseController::class, "index"]);
    Route::get("/fetch_students/{school_code}/{coursecode}", [CourseController::class, 'fetch_students']);
    Route::get("/filtercourses/{coursedata}", [CourseController::class, 'filtercourse']);
    Route::post('assign_course', [CourseController::class, "assignCourse"]);
    Route::get('/fetch_assigned_courses/{school_code}', [CourseController::class, 'fetchAssignedCourses']);
    Route::get('/program_students/{school_code}', [CourseController::class, "fetchProgramStudents"]);
    Route::get('/course_students/{school_code}', [CourseController::class, "fetchCourseStudents"]);
    Route::get('/filterStudentPerCourse/{data}', [CourseController::class, 'filterStudentPerCourse']);
    Route::get('/filterStudentPerProgram/{data}', [CourseController::class, 'filterStudentPerProgram']);
    Route::post('/register_course', [CourseController::class, 'registerStudentCourse']);
    Route::post('assigned_courses_delete/{id}', [Coursecontroller::class, 'assignedcoursesdelete']);
});


//Student route
Route::prefix('admin')->group(function () {
    Route::post('reset_password', [AdminController::class, "resetPassword"]);
    Route::post('forgot_password', [AdminController::class, "forgotPassword"]);
});

//Manage users routes
Route::prefix('user')->group(function () {
    Route::get('/{school_code}', [UserManagementController::class, 'index']);
    Route::post('add', [UserManagementController::class, 'store']);
    Route::post("/delete/{userEmail}", [UserManagementController::class, 'destroy']);
    Route::post('/update', [UserManagementController::class, 'update']);
    Route::get("/filteruser/{userdata}", [UserManagementController::class, 'filterUser']);
});

//Route for message 
// Route::resource("message", MessageSMSController::class);
Route::prefix("message")->group(function () {
    Route::get("sms/{schoolCode}", [AdminMessageController::class, "fetchSms"]);
    Route::get("email/{schoolCode}", [AdminMessageController::class, "fetchEmail"]);
    Route::post("sms", [AdminMessageController::class, "sendSms"]);
    Route::post("email", [AdminMessageController::class, "sendEmail"]);
});

Route::prefix('staff_dashboard')->group(function () {
    Route::get('/students/{course}', [StaffStudentController::class, 'index']);
});

Route::prefix('staff_message')->group(function () {
    Route::post('email', [StaffMessageController::class, 'sendEmail']);
});

Route::prefix("notice")->group(function () {
    Route::get("fetch_curr_notice/{schoolCode}", [StaffNoticeController::class, 'fetchCurrNotice']);
    Route::get("fetch_prev_notice/{schoolCode}", [StaffNoticeController::class, 'fetchPrevNotice']);
    Route::get("fetch_all_notice/{schoolCode}", [StaffNoticeController::class, 'fetchAllNotice']);
    Route::get("notice_staff/{schoolCode}", [StaffNoticeController::class, 'noticeStaff']);
    Route::post("update", [StaffNoticeController::class, 'update']);
    Route::post("add_notice", [StaffNoticeController::class, 'store']);
});

//Api for payment
Route::prefix('payment')->group(function () {
    Route::post('/all_payment/{school_code}', [PaymentsController::class, 'index']);
    Route::post('/store', [PaymentsController::class, 'store']);
    Route::post('/delete', [PaymentsController::class, 'deleteAllPayment']);
    Route::post("/fetch_student_balance", [PaymentsController::class, 'fetchStudentBalance']);
    Route::post("/fetch_student_arrears", [PaymentsController::class, 'fetchStudentArrears']);
    Route::post("/fetch_student_total_paid", [PaymentsController::class, 'fetchStudentTotalPayment']);
    Route::post("/fetch_student_overall_paid", [PaymentsController::class, 'fetchStudentOverallPayment']);
    Route::post("/fetch_student_bill", [PaymentsController::class, 'fetchStudentBill']);
    Route::post("/fetch_student", [PaymentsController::class, 'fetchStudent']);
    Route::post("/fetch_debtors", [PaymentsController::class, 'debtors']);
    Route::post("/fetch_full_payment", [PaymentsController::class, 'fullPayment']);
    Route::post("/fetch_payment_history", [PaymentsController::class, 'paymentHistory']);
    Route::get("/fetch_daily_payment/{schoolCode}", [PaymentsController::class, 'dailyPayment']);
    Route::get("/filter_fetch_daily_payment/{schoolCode}/{branchCode}", [PaymentsController::class, 'filterDailyPayment']);
    Route::post("/delete_payment_history", [PaymentsController::class, 'deletePaymentHistory']);
    Route::post('/paymentStudentBills', [PaymentsController::class, 'fetchStudentBills']);
    Route::post("/addOfflinePayment", [PaymentsController::class, "makeOfflinePayment"]);
    Route::get("/fetchDebtors/{school_code}", [PaymentsController::class, 'fetchDebtors']);
    Route::get("/fetchPaymentHistory/{school_code}", [PaymentsController::class, 'fetchPaymentHistory']);
    Route::get("/fetchDailyPayment/{school_code}", [PaymentsController::class, 'fetchDailyPayment']);
    Route::get("/fetchFullPayment/{school_code}", [PaymentsController::class, 'fetchFullPayment']);
    Route::post("/fetch_payment_ledger", [PaymentsController::class, 'fetchPaymentLedger']);
});

//Api route for bills
Route::prefix("bill")->group(function () {
    Route::get("/fetch_prog_bill_items_billing/{school_code}/{stuCode}", [BillController::class, 'fetchProgBillItemsForBilling']);
    Route::get("/fetch_student_bill_items_billing/{school_code}/{stuCode}", [BillController::class, 'fetchStudentBillItemsForBilling']);
    Route::get("/fetch_ind_prog_semester/{school_code}/{progCode}", [BillController::class, 'fetchProgramSemesterForIndBill']);
    Route::get("/fetch_prog_semester/{school_code}/{progCode}", [BillController::class, 'fetchProgramSemester']);
    Route::get("/fetch_individual_bill_items/{school_code}/{studentNumber}", [BillController::class, 'fetchIndividualBillItems']);
    Route::post("/fetch_program_bill/{school_code}", [BillController::class, 'fetchProgramBill']);
    Route::post("/fetch_student_bill/{school_code}", [BillController::class, 'fetchStudentBill']);
    Route::get("/fetch_prog_bill_items/{school_code}/{batchNo}", [BillController::class, 'fetchProgrammeBillItems']);
    Route::post("/add_programme_bill", [BillController::class, 'addProgrammeBill']);
    Route::post("/fetch_student_total_bill", [BillController::class, 'fetchStudentTotalBill']);
    Route::get("/fetch_bill_item/{school_code}", [BillController::class, 'index']);
    Route::post("/add_bill_item", [BillController::class, 'addBillItem']);
    Route::post("/add_bill", [BillController::class, 'addBill']);
    Route::post("/add_prog_bill", [BillController::class, 'addProgBillItem']);
    Route::post("/destroyBillItem/{billcode}/{schoolcode}", [BillController::class, 'destroyBillItem']);
    Route::post("/update_bill_item", [BillController::class, 'updateBllItem']);
    Route::post("/addStudentBill", [BillController::class, 'addStudentBill']);
    Route::post("/destroyStudentBill/{billcode}/{studentcode}/{schoolcode}", [BillController::class, 'destroyStudentBill']);
    Route::post("upateStudentBill", [BillController::class, 'updateStudentBill']);
    Route::post("/studentAmount/{school_code}/{selectedStudent}", [BillController::class, 'studentAmount']);
    Route::get("/filterbill/{billdata}", [BillController::class, 'filterBill']);
    Route::post("/addBillItemAmount", [BillController::class, 'addBillItemAmount']);
    Route::get("/FetchBillItemAmount/{school_code}", [BillController::class, 'FetchBillItemAmount']);
    Route::post('/updateBillItemAmount', [BillController::class, 'updateBillItemAmount']);
    Route::post('/destroyBillItemAmount', [BillController::class, 'destroyBillItemAmount']);
    Route::get("/filterBillAmount/{billdata}", [BillController::class, 'filterBillAmount']);
    Route::post('/discount_individual_bill', [BillController::class, 'discountIndividualBill']);
    Route::post('/delete_student_bill_item', [BillController::class, 'deleteStudentBill']);
    Route::post('/update_individual_bill', [BillController::class, 'updateIndividualBill']);
});

Route::prefix('dashboard')->group(function () {
    Route::get("/total_student_by_prog/{school_code}", [DashbaordController::class, 'fetchTotalStudentByProg']);
    Route::get("/fetch_class_breakdown/{school_code}", [DashbaordController::class, 'fetchClassBreakdown']);
});

//Route for Expenditure
Route::prefix("expenditure")->group(function () {
    Route::get("fetch_category/{schoolCode}", [ExpenditureController::class, "fetchCategory"]);
    Route::get("fetch/{schoolCode}", [ExpenditureController::class, "index"]);
    Route::post("add_category", [ExpenditureController::class, "addCategory"]);
    Route::post("update/category", [ExpenditureController::class, "updateCategory"]);
    Route::post("add", [ExpenditureController::class, "store"]);
    Route::post("update", [ExpenditureController::class, "update"]);
    Route::post("delete/{id}/{schoolCode}", [ExpenditureController::class, "destroy"]);
    // Route::get("delete/category/{id}", [ExpenditureController::class, "deleteCat"]);
    Route::post("category/delete/{code}", [ExpenditureController::class, 'DeleteCategory']);
});

Route::prefix('supplier')->group(function () {
    Route::get('/all', [SupplierController::class, 'index']);
    Route::post('/create', [SupplierController::class, 'create']);
    Route::post('/update', [SupplierController::class, 'update']);
    Route::post('/delete', [SupplierController::class, 'delete']);
});

Route::prefix('inventory-item')->group(function () {

    Route::get('/all', [InventoryItemController::class, 'index']);
    Route::post('/create', [InventoryItemController::class, 'create']);
    Route::post('/update', [InventoryItemController::class, 'update']);
    Route::post('/delete', [InventoryItemController::class, 'delete']);
});

Route::prefix('inventory')->group(function(){
    Route::get('/all', [InventoryStoreController::class, 'index']);
    Route::post('/create', [InventoryStoreController::class, 'create']);
    Route::post('/update', [InventoryStoreController::class, 'update']);
    Route::post('/delete', [InventoryStoreController::class, 'delete']);
});

Route::prefix('supplier-member')->group(function () {
    Route::get('/all', [SupplierMemberController::class, 'index']);
    Route::post('/create', [SupplierMemberController::class, 'create']);
    Route::post('/update', [SupplierMemberController::class, 'update']);
    Route::post('/delete', [SupplierMemberController::class, 'delete']);
});


//Route for Requisition
Route::prefix("requisition")->group(function () {
    Route::get("fetch_category/{schoolCode}", [RequestController::class, "fetchCategory"]);
    Route::get("fetch/{schoolCode}", [RequestController::class, "index"]);
    Route::post("add_category", [RequestController::class, "addItem"]);
    Route::post("update_category", [RequestController::class, "updateItem"]);
    Route::post("add", [RequestController::class, "store"]);
    Route::post("delete/{id}/{schoolCode}", [RequestController::class, "destroy"]);
    Route::post("delete/{id}", [RequestController::class, "deleteCat"]);
});
// MOBILE API ROUTES
Route::prefix('mobile')->group(function () {
    Route::post("login", [AuthController::class, "login"]);
    Route::post("password_reset", [AuthController::class, "passwordReset"]);
    Route::post("forgot_password", [AuthController::class, "forgotPassword"]);
    Route::post("register_course", [MobileStudentController::class, "registerCourse"]);
    Route::get("check_bill_registration/{schoolCode}/{studentCode}", [MobileStudentController::class, "checkBillAndRegistration"]);
    Route::post("fetch_student_courses", [MobileStudentController::class, "fetchStudentCourses"]);
    Route::post("get_student_arrears", [MobileStudentController::class, "fetchStudentArrears"]);

    Route::prefix("notices")->group(function () {
        Route::get("/type/{usertype}/{schoolCode}", [NoticeController::class, 'index']);
    });

    Route::prefix("bill")->group(function () {
        Route::post('/filter_bill', [MobileStudentController::class, 'fetchStudentBillHistory']);
        Route::post('/fetch_total_bill', [MobileStudentController::class, 'fetchStudentTotalBill']);
    });

    Route::prefix("payment")->group(function () {
        Route::post('/fetch_total_payment', [MobileStudentController::class, 'fetchStudentTotalPayment']);
        Route::post('/history', [MobileStudentController::class, 'fetchPaymentHistory']);
    });
    Route::get("all_students/{staffNo}", [MobileStudentController::class, "allStudents"]);
    Route::get("lecturer_courses/{staffNo}", [MobileStaffController::class, "allStaffCourses"]);
    Route::post('add_suggestion', [MobileStudentController::class, 'suggestion']);
    Route::post('add_service', [MobileStudentController::class, 'stu_service']);
    Route::get('all_services', [MobileStudentController::class, 'service_dropdown']);
    Route::get('all_requests', [MobileStudentController::class, 'get_requests']);
    Route::get('all_stuRequests/{userId}', [MobileStudentController::class, 'get_stuRequest']);
    Route::post('delete_request/{id}', [MobileStudentController::class, 'deleteService']);
    Route::post('edit_request/{id}', [MobileStudentController::class, 'editService']);
    Route::post('assessment', [MobileStudentController::class, 'assessment']);
    Route::post('my_courses', [MobileStudentController::class, 'student_courses']);
});


Route::prefix("services")->group(function () {
    Route::post('admin_service', [RequestController::class, 'admin_services']);
    Route::post('serviceRequest_update', [RequestController::class, 'updateService'])->name('update_service');
});
