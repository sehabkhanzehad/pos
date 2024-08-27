<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTP;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    public function showRegistrationPage(): View
    {
        return view('pages.auth.registration');
    }

    public function showLoginPage(): View
    {
        return view('pages.auth.login');
    }

    public function showSendOTPPage(): View
    {
        return view('pages.auth.otp-send');
    }

    public function showVerifyOTPPage(): View
    {
        return view('pages.auth.otp-verify');
    }

    public function showResetPasswordPage(): View
    {
        return view('pages.auth.password-reset');
    }


    public function userRegistration(Request $request)
    {
        // $request->validate([
        //     'username' => ['required', 'string', 'max:255'],
        //     'name' => ['required', 'string', 'max:255'],
        //     'phone' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8'],
        // ]);

        try {
            User::create($request->all());
            return response()->json([
                'status' => 'success',
                'url' => route('user.login'),
                'message' => 'User registered successfully.'
            ], 200);

            // } catch (Exception $th) {
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User registration failed.',
                // 'error' => $th->getMessage(),
            ], 200);
        }
    }



    public function userLogin(Request $request)
    {
        // $request->validate([
        //     'email' => ['required', 'string', 'email', 'max:255'],
        //     'password' => ['required', 'string', 'min:8'],
        // ]);


        $user = User::where('email', $request->email)->where('password', $request->password)->first();

        if ($user) {
            // Issue Token for 1 hour
            $token = JWTToken::generateToken($request->input('email'), $user->id, 60 * 60);


            return response()->json([
                'status' => 'success',
                'url' => route('dashboard'),
                'message' => 'Login Successful',
                // 'token' => $token
            ], 200)->cookie('logInToken', $token, 60 * 1); // set cookie for 1 hour
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid Email or Password'
            ], 200);
        }
    }


    // Send OTP for forget password
    public function sendOTP(Request $request)
    {
        $user = $request->email;
        $check = User::where('email', $user)->first();

        if ($check) {

            $otp = rand(100000, 999999);

            // update otp
            $check->update(['otp' => $otp]);

            //send otp
            Mail::to($user)->send(new OTP($otp));


            return response()->json([
                'status' => 'success',
                'url' => route('user.verify-otp'),
                'message' => 'OTP sent successfully.'
            ], 200)->header('X-User-Email', $user)->cookie('userEmail', $user, 5);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found.'
            ], 200);
        }
    }


    public function otpVerify(Request $request)
    {
        $userEmail = $request->cookie('userEmail');
        // $userrEmail = $request->header('userEmail');
        $otp = $request->input('otp');

        if ($userEmail) {
            $check = User::where('email', $userEmail)->where('otp', $otp)->first();
            if ($check) {
                $expirytime = $check->updated_at->addMinute(); // Set the expiry time to 1 minute after updated_at
                // $expirytime = $check->updated_at->addMinutes(3); // Set the expiry time to 3 minute after updated_at

                if (now()->greaterThan($expirytime)) { // If current time is greater than the expiry time
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'OTP expired, Please try again.'
                    ], 200);
                } else {
                    // Update OTP
                    $check->update(['otp' => "0"]);

                    // Issue Token for 5 minutes
                    $passResetToken = JWTToken::generateToken($userEmail, $check->id, 60 * 5);

                    // remove cookie from browser after verify OTP


                    // Set Cookie
                    return response()->json([
                        'status' => 'success',
                        'url' => route('user.reset-password'),
                        'message' => 'OTP verified successfully.',
                        // 'token' => $passResetToken
                    ], 200)->cookie('passResetToken', $passResetToken, 5);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please enter valid OTP.'
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong. Please try again.'
            ], 200);
        }
    }


    public function resetPassword(Request $request)
    {
        $email = $request->header("email");
        // $id = $request->attributes->get('id');


        // $user = User::where('email', $email)->where('id', $id)->first();
        $user = User::where('email', $email)->first();

        try {
            // if ($user) {

            $user->update([
                'password' => $request->password
            ]);
            return response()->json([
                'status' => 'success',
                'url' => route('user.login'),
                'message' => 'Password reset successfully.',
            ]);

            // } else {
            //     return response()->json([
            //         'status' => 'failed',
            //         'message' => 'User not found.'
            //     ], 200);
            // }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong. Please try again.',
                // 'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function userLogout(Request $request)
    {
        return redirect(route('user.login'))->cookie('logInToken', null, -1);
    }





}
