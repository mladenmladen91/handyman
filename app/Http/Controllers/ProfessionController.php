<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Profession;

class ProfessionController extends Controller
{
    // function for adding Profession
    public function addProfession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $profession = Profession::create(["name" => $request->name]);

        return response()->json(['success' => true, 'profession' => $profession], 200);
    }

    // function for update Profession
    public function updateProfession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $profession = Profession::find($request->id);

        if(!$profession){
            return response()->json(['success' => false, 'message' => 'Profession not found'], 404); 
        }

        $input = $request->except(["id"]);

        $profession->update($input);

        return response()->json(['success' => true, 'profession' => $profession], 200);
    }

    // function for deleting Profession
    public function deleteProfession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $profession = Profession::find($request->id);

        if(!$profession){
            return response()->json(['success' => false, 'message' => 'Profession not found'], 404); 
        }

        $profession->delete();

        return response()->json(['success' => true, 'message' => 'Profession deleted'], 200);
    }

    // function for getting professions
    public function getProfessions(Request $request)
    {
       $professions = Profession::all();

        return response()->json(['success' => true, 'professions' => $professions], 200);
    }
}
