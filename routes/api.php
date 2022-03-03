<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Models\Employee;

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


Route::post('/login', [AuthController::class , 'login']);



// Public Route Employee

Route::get('/employees', [EmployeeController::class , 'index']);
Route::get('/employees/{id}', [EmployeeController::class , 'show']);
Route::post('/employees', [EmployeeController::class , 'store']);
Route::put('/employees/{id}', [EmployeeController::class , 'update']);
Route::get('/employees/search/{name}', [EmployeeController::class , 'search']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
