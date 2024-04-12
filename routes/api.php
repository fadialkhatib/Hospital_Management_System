<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\SessionController;
use App\Http\Middleware\MyAuthentication;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\Login as ModelsLogin;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/start',[SessionController::class,'startSession']);

################# HANDSHAKE MIDDLEWARE  ##########
Route::group(['middleware'=>'handshake'],function(){
    Route::post('/login',[LoginController::class,'login']);
    
    Route::post('/hash', function(Request $request)
    {
        return  Department::where(['id'=>$request->department_id,
                                   'password'=>$request->password])
                                   ->update([
                'password'=>Hash::make($request->password)
                                   ]);
        }
    );
    

########### AUTH MIDDLEWARE ##################
Route::group(['middleware'=>'myauth'],function(){
    Route::post('/logout',[LoginController::class,'logout']);

    ########  Deparment Routes  ##########
    Route::get('/deps/all',[DepartmentController::class,'all_deps']);
    Route::post('/deps/show',[DepartmentController::class,'show_dep']);
    Route::post('/deps/patients',[DepartmentController::class,'all_p_in_dep']);
    Route::post('/deps/accept_resident',[DepartmentController::class,'accept_resident']);
    Route::post('/deps/get_residents',[DepartmentController::class,'get_residents']);
    
    ########### Patient Routes #########
    Route::post('/patient/add',[PatientController::class,'add_patient']);
    Route::post('/patient/file',[PatientController::class,'patient_file']);
    Route::post('/patient/transfer',[PatientController::class,'transfer_patient_dep']);
    Route::post('/patient/attach/test',[PatientController::class,'test_result']);
    Route::post('/patient/attach/emergency',[PatientController::class,'emergency']);
    Route::post('/patient/attach/x-ray',[PatientController::class,'X_ray_result']);
    Route::post('/patient/search',[PatientController::class,'searchbypatient']);

    ##################### Queue Test ##################
    Route::post('/test/request',[QueueController::class,'request_test']);
    Route::get('/test/all',[QueueController::class,'all_queue_patients']);
    Route::post('/test/spatient',[QueueController::class,'get_p_from_queue']);
    ######  Queue Xray #####
    Route::post('/xray/request',[QueueController::class,'request_xray']);
    Route::get('/xray/all',[QueueController::class,'all_xqueue_patients']);
    Route::post('/xray/spatient',[QueueController::class,'get_p_from_xqueue']);
    
});
});
