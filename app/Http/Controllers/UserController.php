<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->user();
        return response()->json($data);
    }


    //Reset Password
    public function resetpassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:2',
            'newpassword' => 'required|min:2|confirmed',
            'newpassword_confirmation' => 'required|min:2'
        ]);
        $currentPassword = auth()->user()->password;

        if (Hash::check($request->password, $currentPassword)) {
            $user = auth()->user();
            $user->password = Hash::make($request->newpassword);
            $user->update();
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'error',
                'errors' => ['Invalid Credentials'],
            ], 401);
        }
        
        return response()->json('Password Changed');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|confirmed',
            'gender' => 'required|string',
        ]);

        $user = new User();
        $user->name = $request->fullName;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->gender = $request->gender;
        $user->save();

        $token = $user->createToken('apiToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    //log in user
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required'
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'error',
                    'errors' => ['Invalid Credentials'],
                ], 401);
            }

            $token = $user->createToken('apiToken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response([$response]); 


        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }

    
        
    }


    //log out the user
    public function logout(Request $request)
    {

        auth()->user()->tokens()->delete();

        return response(['message' => 'logged out']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
