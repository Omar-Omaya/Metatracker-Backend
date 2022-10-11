<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\WeekEndController;
use App\Http\Controllers\StatisticsHourController;
use App\Http\Controllers\MonthlyProductivityController;
use App\Http\Controllers\CompanyController;









use App\Http\Controllers\MessageEmployeeController;
use App\Models\History;
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

// Public Routes Authantication


Route::post('/register', [AuthController::class , 'register']);

Route::post('/addCompany', [CompanyController::class , 'store']);


// Public Routes Admin Authantication


Route::post('/admin/register', [AdminController::class , 'register']);

Route::post('/admin/login', [AdminController::class , 'login']);

// Public Route Employee

Route::get('/employees/{id}', [EmployeeController::class , 'show']);
Route::post('/employees', [EmployeeController::class , 'store']);
Route::put('/employees/{id}', [EmployeeController::class , 'update']);
Route::get('/employees/search/{name}', [EmployeeController::class , 'search']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
Route::put('/mob_token', [EmployeeController::class, 'mobile_token']);
Route::get('/dis', [EmployeeController::class, 'distance']);
Route::put('/is_here/{id}', [EmployeeController::class, 'is_Here']);

Route::post('/storeimage/{id}', [PhotoController::class, 'storeImage']);

Route::get('/getimage/{id}', [PhotoController::class, 'getImage']);
                             
// Public Route Histroy

Route::get('/histories', [HistoryController::class , 'index']);
Route::get('/check_in_today/{company_id}', [HistoryController::class , 'checkInToday']);

Route::get('/latlngemp/{id}', [HistoryController::class , 'latlngEmp']);



Route::get('/histories/{id}', [HistoryController::class , 'show']);


Route::get('/histories/search/{name}', [HistoryController::class , 'search']);
Route::delete('/histories/{id}', [HistoryController::class, 'destroy']);

Route::get('/count-out/{company_id}', [HistoryController::class , 'getOutOfZoneToday']);
Route::get('/count-in/{company_id}', [HistoryController::class , 'getInOfZoneToday']);
Route::get('/currentlocation/{id}', [HistoryController::class , 'getCurrentLocation']);
Route::get('/absence_today/{company_id}', [HistoryController::class , 'getAbsenceToday']);
Route::get('/attend_today', [HistoryController::class , 'getAttendanceToday']);
Route::get('/totalHour/{id}', [HistoryController::class , 'totalHour']);
Route::get('/calcYear/{id}', [StatsController::class , 'calculateYearly']);


// dashboard
Route::get('/inZoneLate/{company_id}', [StatsController::class , 'inZoneLate']);
Route::get('/inZoneLateEmp', [HistoryController::class , 'inZoneLateEmp']);


Route::get('/outZoneNoexcuse/{company_id}', [StatsController::class , 'outZoneNoexcuse']);
Route::get('/outZoneholiday', [StatsController::class , 'outZoneholiday']);



// Public Route Message
Route::get('/msg/{id}', [MessageController::class , 'show']);
Route::get('/msgs', [MessageController::class , 'getMessage']);

Route::put('/msg/{id}', [MessageController::class , 'update']);
Route::delete('/msg/{id}', [MessageController::class , 'destroy']);

Route::post('/msg', [MessageController::class , 'store']);
Route::get('/msg', [MessageController::class , 'index']);

Route::post('/msgemp/{id}', [MessageController::class , 'messageEmployee']);
Route::get('/getmsgemp', [MessageController::class , 'getMessageEmpMobile']);
Route::get('/getallmsgemp/{admin_id}', [MessageController::class , 'getAllMessageEmp']);


Route::post('/msgdep/{id}', [MessageController::class , 'messageDepartment']);
Route::post('/announc', [MessageController::class , 'messageAnnouncement']);
Route::get('/getmegs', [MessageController::class , 'getMessagesMobile']);

Route::get('/getallmsgdep/{admin_id}', [MessageController::class , 'getAllMessageDep']);
Route::get('/getannounc/{admin_id}', [MessageController::class , 'getAllMessageAnnounc']);











// Public Route Department
// Route::get('/get-deps', [DepartmentController::class , 'readAllDepartment']);
// Route::post('/store-dep', [DepartmentController::class , 'store']);
// Route::get('/count-dep', [DepartmentController::class , 'countAllDepartment']);
// Route::get('/emp-dep', [DepartmentController::class , 'empOfDepartments']);


Route::get('/emp-dep', [DepartmentController::class , 'empOfDepartments']);

//Public Route Notification
Route::get('/getNotification/{id}', [NotificationController::class, 'getNotification']);

Route::post('/addReply', [NotificationController::class, 'addReply']);

Route::delete('/deleteNotify/{id}', [NotificationController::class, 'deleteNotification']);




Route::post('/list-of-employees', [AuthController::class , 'excel']);
Route::post('/store-dep', [DepartmentController::class , 'store']);
Route::get('/payroll/{company_id}', [StatisticsHourController::class , 'payroll']);


Route::Post('/transfersalary', [MonthlyProductivityController::class , 'transferSalary']);
Route::get('/getMonthlyProductivity/{id}', [MonthlyProductivityController::class , 'getMonthlyProductivity']);

// public Route Holiday

Route::post('/store-holi', [HolidayController::class , 'store']);

//public Route WeekEnd

Route::post('/weekend', [WeekEndController::class , 'store']);
Route::post('/week', [WeekEndController::class , 'setHolidayToEmployee']);

// public Rout Working Hour

Route::get('/Actual/{id}', [StatisticsHourController::class , 'getTotalActualHours']);

// Mobile api

Route::post('/login', [AuthController::class , 'login']);
Route::post('/histories', [HistoryController::class , 'store']);
Route::put('/histories', [HistoryController::class , 'update']);
Route::put('/latlng', [HistoryController::class , 'updateLatLong']);
Route::get('/attend', [HistoryController::class, 'countAttendanceDay']);
Route::get('/absence', [HistoryController::class , 'getAbsenceDay']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/count-dep', [DepartmentController::class , 'countAllDepartment']);
    Route::get('/get-deps', [DepartmentController::class , 'readAllDepartment']);
    Route::get('/get-employees', [EmployeeController::class , 'getAllEmployees']);
    // Route::get('/get-employees', [EmployeeController::class , 'getAllEmployees']);
    Route::get('/number-of-employees', [EmployeeController::class , 'index']);
    
    Route::get('/calcOut', [StatsController::class , 'calcgetOutOfZoneMonth']);
    Route::get('/calcIn', [StatsController::class , 'calcgetInOfZoneMonth']);
    
    Route::get('/calcOutEmp/{id}', [StatsController::class , 'calcgetOutOfZoneMonthPerEmp']);
    Route::get('/calcInEmp/{id}', [StatsController::class , 'calcgetInOfZoneMonthPerEmp']);

    

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});
