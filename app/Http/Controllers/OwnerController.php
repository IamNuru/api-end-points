<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    //store new todo owner
    public function store(Request $request){
        $request->validate([
            'username' => 'required|string|min:4|max:12|unique:owners,username',
            'password' => 'required|string|min:4|max:15'
        ]);
        $owner = new Owner;
        $owner->username = $request->username;
        $owner->password = $request->password;
        $owner->save();

        return response()->json(['message' => 'Account created successful', 'data' =>$owner]);
    }




    //update todo owner
    public function resetpassword(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required|string|min:3|max:12'
        ]);
        $owner = Owner::where('username', $request->username)->first();
        if ($owner) {
            $owner->password = $request->password;
            $owner->update();
            return response()->json(['message' => 'Update successful', 'data' =>$owner]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'error',
                'errors' => ['Invalid Credentials'],
            ], 401);
        };
    }


    
    public function check(Request $request){
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $owner = Owner::where('username', $request->username)->first();
        if(!$owner){
            //return response()->json('Sorry, the username does not match our records');
            return response()->json([
                'status' => 'error',
                'message' => 'error',
                'errors' => ['Invalid Credentials'],
            ], 401);
        }elseif ($owner->password !== $request->password) {
            return response()->json([
                'status' => 'error',
                'message' => 'error',
                'errors' => ['Invalid Credentials'],
            ], 401);
            //return response()->json(['message' => 'Sorry, Invalid credentials']);
        }else{
            return response()->json(['message' => 'Login successful', 'data' =>$owner]);
        }

    }
}
