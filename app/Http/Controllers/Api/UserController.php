<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255',
            'password' => 'required',
            'phone' => 'required|string|min:5|max:11',
            'date_of_birth' => 'required|date_format:Y-m-d|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first(),
            ], 422);
        }

        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token, 'name' => $user->name];

        return response()->json([
            'data'  => $response,
        ], 200);
    }


    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first(),
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token, 'name' => $user->name];
                return response()->json([
                    'data'  => $response,
                ], 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function getUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first(),
            ], 422);
        }

        $user = User::find($request->id);

        return response()->json([
            'user'  => $user,
        ], 200);

    }

    public function allUsers()
    {
        $users = User::paginate(10);

        if(!$users)
        {
            return response()->json([
                'message'  => 'Users not found',
            ], 200);
        }

        return response()->json([
            'users'  => $users,
        ], 200);
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first(),
            ], 422);
        }

        $user=User::find($request->id);
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user->update($request->all());

        return response()->json([
            'user'  => $user,
            'message' => 'user updated successfully',
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user)
        {
            return response()->json([
                'message'  => 'User not found',
            ], 422);

        }
        User::destroy($id);

        return response()->json([
            'message' => 'user deleted successfully',
        ], 200);
    }
}
