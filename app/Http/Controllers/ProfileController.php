<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;


class ProfileController extends Controller
{
    public function showProfilePage(): View
    {
        return view("pages.dashboard.profile");
    }
    public function userDetails(Request $request)
    {
        try {
            $email = $request->header('email');
            $id = $request->header('id');
            $user = User::where('email', $email)->where('id', $id)->first();
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $email = $request->header('email');
            $id = $request->header('id');

            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $password = $request->input('password');

            $user = User::where('email', $email)->where('id', $id)->first();

            $user->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'password' => $password,
                'mobile' => $request->input('mobile'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
