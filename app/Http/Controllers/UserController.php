<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\City;

class UserController extends Controller
{


    // function for adding user
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "surname" => "required",
            "email" => "required",
            "password" => "required",
            "role_id" => "required",
            "profession_id" => "required",
            "cities" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $input = $request->all();

        if ($file = $request->file('image')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images/profile', $name);
            $input['image'] = 'images/profile/' . $name;
        }

        $input["password"] = bcrypt($request->password);

        $user = User::create($input);
        $cities = json_decode($request->cities);
        $user->cities()->attach($cities);

        return response()->json(['success' => true, 'user' => $user], 200);
    }
    // function for updating user
    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $user = User::find($request->id);

        if(!$user){
            return response()->json(['success' => false, 'message' => 'User not found'], 404); 
        }

        $input = $request->all();

        if ($request->password) {
            $input["password"] = bcrypt($request->password);
        }

        if ($file = $request->file('image')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images/profile', $name);
            if ($user->image) {
                unlink(public_path() . '/' . $user->image);
            }
            $input['image'] = 'images/profile/' . $name;
        }

        $user->update($input);
        $cities = json_decode($request->cities);
        $user->cities()->sync($cities);

        return response()->json(['success' => true, 'user' => $user], 200);
    }
    // function for deleting user
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $user = User::find($request->id);

        if(!$user){
            return response()->json(['success' => false, 'message' => 'User not found'], 404); 
        }

        unlink(public_path() . '/' . $user->image);

        $user->cities()->detach();

        $user->delete();

        return response()->json(['success' => true, 'message' => "User deleted"], 200);
    }

    // function for getting user list and for candidates list
    public function getUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required|int|min:0',
            'limit' => 'required|int',
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $user = auth()->user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['success' => false, 'message' => "User unauthorized"], 401);
        }


        $users = User::with(["profession", "cities"]);

        $count = $users->count();

        $users = $users->limit($request->get("limit"))
            ->offset($request->get("offset"))
            ->orderBy("created_at", "DESC")
            ->get();

        return response()->json(['success' => true, 'users' => $users, 'count' => $count], 200);
    }

    // function for changing profile
    public function changeProfile(Request $request)
    {
        

        $userAuth = auth()->user();

        if(!$userAuth){
            return response()->json(['success' => false, 'message' => 'User not found'], 404); 
        }

        $user = User::find($userAuth->id);

        $input = $request->all();

        if ($request->password) {
            $input["password"] = bcrypt($request->password);
        }

        if ($file = $request->file('image')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images/profile', $name);
            if ($user->image) {
                unlink(public_path() . '/' . $user->image);
            }
            $input['image'] = 'images/profile/' . $name;
        }

        $user->update($input);
        $cities = json_decode($request->cities);
        $user->cities()->sync($cities);

        return response()->json(['success' => true, 'user' => $user], 200);
    }
}
