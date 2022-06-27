<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatsController;

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

Route::post('/login', [AuthController::class , 'login']);
Route::post('/list-of-employees', [AuthController::class , 'excel']);



// Public Routes Admin Authantication


Route::post('/admin-register', [AdminController::class , 'register']);

Route::post('/admin/login', [AdminController::class , 'login']);

// Public Route Employee

Route::get('/number-of-employees', [EmployeeController::class , 'index']);
Route::get('/get-employees', [EmployeeController::class , 'getAllEmployees']);
Route::get('/employees/{id}', [EmployeeController::class , 'show']);
Route::post('/employees', [EmployeeController::class , 'store']);
Route::put('/employees/{id}', [EmployeeController::class , 'update']);
Route::get('/employees/search/{name}', [EmployeeController::class , 'search']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
Route::put('/mob_token/{id}', [EmployeeController::class, 'mobile_token']);
Route::get('/dis', [EmployeeController::class, 'distance']);



// Public Route Histroy

Route::get('/histories', [HistoryController::class , 'index']);
Route::get('/check_in_today', [HistoryController::class , 'checkInToday']);

Route::get('/histories/{id}', [HistoryController::class , 'show']);
Route::post('/histories', [HistoryController::class , 'store']);
Route::put('/histories/{id}', [HistoryController::class , 'update']);
Route::get('/histories/search/{name}', [HistoryController::class , 'search']);
Route::delete('/histories/{id}', [HistoryController::class, 'destroy']);
Route::get('/attend/{id}', [HistoryController::class, 'countAttendanceDay']);
Route::get('/absence', [HistoryController::class , 'getAbsenceDay']);
Route::get('/count-out', [HistoryController::class , 'getOutOfZone']);


Route::get('/count-in', [HistoryController::class , 'getInOfZone']);
Route::get('/absence_today', [HistoryController::class , 'getAbsenceToday']);
Route::get('/attend_today', [HistoryController::class , 'getAttendanceToday']);
Route::get('/totalHour/{id}', [HistoryController::class , 'totalHour']);
Route::get('/calcYear/{id}', [StatsController::class , 'calculateYearly']);

Route::get('/calcOut', [StatsController::class , 'calcgetOutOfZoneMonth']);
Route::get('/calcIn', [StatsController::class , 'calcgetInOfZoneMonth']);

Route::get('/calcOutEmp', [StatsController::class , 'calcgetOutOfZoneMonthPerEmp']);
Route::get('/calcInEmp', [StatsController::class , 'calcgetInOfZoneMonthPerEmp']);












// Public Route Message
Route::get('/msg', [MessageController::class , 'index']);
Route::get('/msg/{id}', [MessageController::class , 'show']);
Route::post('/msg', [MessageController::class , 'store']);
Route::put('/msg/{id}', [MessageController::class , 'update']);
Route::delete('/msg/{id}', [MessageController::class , 'destroy']);


// Public Route Department
Route::get('/dep', [DepartmentController::class , 'readAllDepartment']);
Route::post('/dep', [DepartmentController::class , 'store']);
Route::get('/count-dep', [DepartmentController::class , 'countAllDepartment']);

Route::get('/get-dep', [DepartmentController::class , 'getAllDepartment']);
Route::get('/emp-dep', [DepartmentController::class , 'empOfDepartments']);


//Public Route Notification
Route::get('/notification', [NotificationController::class, 'notificationTesting']);




Route::group(['middleware' => ['auth:sanctum']], function () {



});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
