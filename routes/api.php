<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\democontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get("demo",[democontroller::class,"demo"]);
Route::group(['middleware'=>'api','prefix'=>'auth'],function($router){
Route::post("/register",[AuthController::class,'register']);
Route::post("/login",[AuthController::class,'login']);
Route::get("/profile",[AuthController::class,'profile']);
Route::post("/logout",[AuthController::class,'logout']);
//category routes
Route::post("/category",[AuthController::class,'addCategory']);
Route::get("/category",[AuthController::class,'viewCategory']);
Route::post("/category/{category}",[AuthController::class,'updateCategory']);
Route::delete("/category/{category}",[AuthController::class,'deleteCategory']);

//testimonials routes 

Route::post("/testimonials",[AuthController::class,'createTestimonials']);
Route::get("/testimonials",[AuthController::class,'viewTestimonial']);
Route::post("/testimonials/{testimonial}",[AuthController::class,'updateTestimonials']);
Route::delete("/testimonials/{testimonial}",[AuthController::class,'deleteTestimonials']);

//project routes 
Route::post("/projects",[AuthController::class,'createProject']);
Route::get("/projects",[AuthController::class,'viewProject']);
Route::put("/projects/{project}",[AuthController::class,'updateProject']);
Route::delete("/projects/{project}",[AuthController::class,'deleteProject']);
//contact routes 
Route::post("/contact",[AuthController::class,'contactus']);
Route::get("/contact",[AuthController::class,'viewcontact']);

});
