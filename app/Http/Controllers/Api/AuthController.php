<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\User;
use App\UserWallet;
use App\UserBank;
use Validator;
use Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {

        $messages = [
            'name.required'    => 'Enter full name!',
            'email.required' => 'Enter an e-mail address!',
            'email' => 'E-mail address exist!',
            'password.required'    => 'Password is required',
            'password_confirmation' => 'The :password and :password_confirmation must match.'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ], $messages);

        $user = User::where('email', $request->get('email'))->first();

        if ($user) {
            return response()->json([
                'status' => 'exist',
                'message' => 'User already exist. please login',
            ], 409);
        } elseif ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 406);
        } else {
            $input = $request->all();
            $input['password'] = Hash::make($request->get('password'));

            $user = User::create($input);

            UserWallet::create([
                'user_id' => $user->id
            ]);
            UserBank::create([
                'user_id' => $user->id
            ]);

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'access_token' => $tokenResult->accessToken,
                'data' => $user->load('wallet','bank'),
            ]);
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials))
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 401);

        if (!Auth::user()->active)
            return response()->json([
                'status' => 'failed',
                'message' => 'Your account is under review'
            ], 401);

        $user = Auth::user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'status' => 'success',
            'access_token' => $tokenResult->accessToken,
            'message' => 'login successful',
            'data' => $user->load('wallet','bank')
        ]);
        
    }

     /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        // $user = Auth::user();
        $request->user()->token()->revoke();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'message' => 'user fetched',
            'data' => $user->load('wallet','bank')
        ]);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'user activated',
            'data' => $user
        ]);
    }
}
