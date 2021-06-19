<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\City;

class CityController extends Controller
{
    // function for adding city
    public function addCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $city = City::create(["name" => $request->name]);

        return response()->json(['success' => true, 'city' => $city], 200);
    }

    // function for update city
    public function updateCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $city = City::find($request->id);

        if(!$city){
            return response()->json(['success' => false, 'message' => 'City not found'], 404); 
        }

        $input = $request->except(["id"]);

        $city->update($input);

        return response()->json(['success' => true, 'city' => $city], 200);
    }

    // function for deleting city
    public function deleteCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->messages()]);
        }

        $city = City::find($request->id);

        if(!$city){
            return response()->json(['success' => false, 'message' => 'City not found'], 404); 
        }

        $city->delete();

        return response()->json(['success' => true, 'message' => 'City deleted'], 200);
    }

    // function for getting cities
    public function getCities(Request $request)
    {
       $cities = City::all();

        return response()->json(['success' => true, 'cities' => $cities], 200);
    }
}
