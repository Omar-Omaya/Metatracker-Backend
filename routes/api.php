<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
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
Route::post('/excel', [AuthController::class , 'excel']);



// Public Routes Admin Authantication


Route::post('/admin/register', [AdminController::class , 'register']);

Route::post('/admin/login', [AdminController::class , 'login']);

// Public Route Employee

Route::get('/number-of-employees', [EmployeeController::class , 'index']);
Route::get('/get-employees', [EmployeeController::class , 'getAllEmployees']);
Route::get('/employees/{id}', [EmployeeController::class , 'show']);
Route::get('/employee/absence/{id}', [EmployeeController::class , 'getAbsenceDay']);

Route::post('/employees', [EmployeeController::class , 'store']);
Route::put('/employees/{id}', [EmployeeController::class , 'update']);
Route::post('/employees/search/{name}', [EmployeeController::class , 'search']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

// Public Route Histroy

Route::get('/histories', [HistoryController::class , 'index']);
Route::get('/marker-location', [HistoryController::class , 'getLastLocation']);
Route::get('/histories/{id}', [HistoryController::class , 'show']);
Route::post('/histories', [HistoryController::class , 'store']);
Route::put('/histories/{id}', [HistoryController::class , 'update']);
Route::get('/histories/search/{name}', [HistoryController::class , 'search']);
Route::delete('/histories/{id}', [HistoryController::class, 'destroy']);
Route::get('/histories-days/{id}', [HistoryController::class, 'countAttendance']);


// Public Route Message 
Route::get('/msg', [MessageController::class , 'index']);
Route::get('/msg/{id}', [MessageController::class , 'show']);
Route::post('/msg', [MessageController::class , 'store']);
Route::put('/msg/{id}', [MessageController::class , 'update']);
Route::delete('/msg/{id}', [MessageController::class , 'destroy']);




Route::group(['middleware' => ['auth:sanctum']], function () {

    
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
