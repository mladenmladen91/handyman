<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => 0,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        if (!$user) {
            return response()->json(['success' => false, "message" => "Error"], 500);
        }
        return response()->json(['success' => true, "message" => "User successfully registered"], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($request->all())) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['success' => true, 'token' => $token], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorised'], 401);
        }
    }

    //function for checking password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "old_password" => "required",
            "password" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $userAuth = auth()->user();
        if (!$userAuth) {
            return response()->json(['success' => false, 'message' => "User unauthorized"], 401);
        }

        $user = User::find($userAuth->id);

        if (Hash::check($request->old_password, $userAuth->password)) {
            $user->update(["password" => bcrypt($request->password)]);
            return response()->json(['success' => true, 'match' => 1], 200);
        }

        return response()->json(['success' => false, 'match' => 0], 200);
    }

    // function for getting token
    public function token()
    {
        /** @var User $user */
        $user = auth('api')->user();
        $accessToken = $user->createToken('LaravelAuthApp');

        return response()->json(['success' => true, "accessToken" => $accessToken->accessToken], 200);
    }
    // function for logout
    public function logout()
    {
        if (auth()->user()) {
            auth()->user()->logout(); // log the user out of our application
        }
        return response()->json(["success" => true, "message" => "User logout"], 200);
    }

    public function test()
    {
        return response()->json(["test" => "test"]);
    }
}
