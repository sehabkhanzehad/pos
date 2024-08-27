<?php

use App\Helper\JWTToken;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("/", [HomeController::class, "showHomePage"])->name("home.index");
Route::get("/user/register", [UserController::class, "showRegistrationPage"])->name("user.register");
Route::get("/user/login", [UserController::class, "showLoginPage"])->name("user.login");
Route::get("/user/send-otp", [UserController::class, "showSendOTPPage"])->name("user.send-otp");
Route::get("/user/verify-otp", [UserController::class, "showVerifyOTPPage"])->name("user.verify-otp")->middleware("verifyOtpSend");
Route::get("/user/reset-password", [UserController::class, "showResetPasswordPage"])->name("user.reset-password")->middleware("verifyJwtToken");



// User Auth

// Login and Registration
Route::post("/user-registration", [UserController::class, "userRegistration"])->name("register");
Route::post("/user-login", [UserController::class, "userLogin"])->name("login");

// Logout
Route::get("/user/logout", [UserController::class, "userLogout"])->name("user.logout");

// Password Reset
Route::post("/send-otp", [UserController::class, "sendOTP"])->name("send-otp");
Route::post("/otp-verify", [UserController::class, "otpVerify"])->name("verify-otp");
Route::post("/reset-password", [UserController::class, "resetPassword"])->name("reset-password")->middleware("verifyJwtToken");


Route::middleware("authCheck")->group(function () {
    // Dashboard
    Route::get("/dashboard", [DashboardController::class, "showDashboardPage"])->name("dashboard");

    // Profile
    Route::get("/profile", [ProfileController::class, "showProfilePage"])->name("user.profile");
    route::get("/user/details", [ProfileController::class, "userDetails"])->name("user.details");
    Route::post("/profile-update", [ProfileController::class, "updateProfile"])->name("profile.update");

    // Category
    Route::get("/category", [CategoryController::class, "showCategoryPage"])->name("category.index");
    Route::post("/category/create", [CategoryController::class, "store"])->name("category.create");
    Route::get("/category/list", [CategoryController::class, "categoryList"])->name("category.list");
    Route::post("/category/update/{categoryId}", [CategoryController::class, "updateCategory"])->name("category.update");

});
















Route::get('/test-header', function () {

    $user = 'test@example.com';
    // return response(json_encode([
    //     'status' => 'success',
    //     'message' => 'OTP sent successfully.'
    // ], 200))
    // ->header('X-User-Email', $user)
    // ->cookie('userEmail', $user, 5);

    return response()->json([
        'status' => 'success',
        'message' => 'OTP sent successfully.'
    ], 200)
        ->header('X-User-Email', $user)
        ->cookie('userEmail', $user, 5);
});



Route::get("/get-header", function (Request $request) {

    // Retrieve specific header
    $userEmail = $request->header('X-User-Email');

    // Retrieve all headers
    $allHeaders = $request->headers->all();

    // Do something with the header
    return response()->json([
        'userEmail' => $userEmail,
        'allHeaders' => $allHeaders,
    ]);
});


Route::get("/get-header&cookie", function (Request $request) {
    return response()->json([
        'header' => $request->header('X-User-Email'),
        'cookie' => $request->cookie('userEmail')
    ]);
});
Route::get("/check-time", function () {
    // date_default_timezone_set('Asia/Dhaka'); // Replace with your desired time zone
    echo date('Y-m-d H:i:s');
    // return date("Y-m-d H:i:s");
});
