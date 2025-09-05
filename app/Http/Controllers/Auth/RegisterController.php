<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function getSignupForm()
    {
        return view('auth.signup');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname'  => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            User::create([
                'name'     => $request->firstname . ' ' . $request->lastname,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status'   => true,
                'message'  => 'Registration successful',
                'redirect' => route('get.signin.form'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getSigninForm(Request $request)
    {
        return view('auth.login');
    }

    public function postLoginForm(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'   => true,
                'message'  => 'Login successful',
                'redirect' => route('/'),
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => ['email' => ['Invalid email or password']],
        ], 422);
    }

}
