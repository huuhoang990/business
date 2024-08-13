<?php
namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Rules\IdValidator;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    public function getAllProvinces()
    {
        // Specify the columns you want to retrieve
        $columns = ['id', 'full_name', 'code_name'];

        // Retrieve the province with the specified columns
        $provinces = Province::select($columns)->get();

        // Return provinces as a JSON response
        return response()->json([
            'success' => true,
            'data' => $provinces
        ], 200)->header('Content-Type', "application/json;charset=UTF-8");
    }

    public function selectProvince($id)
    {
        // Validate the incoming id parameter
        $validator = validator(['id' => $id], [
            'id' => ['required', new IdValidator('province')],
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Specify the columns you want to retrieve
        $columns = ['id', 'full_name', 'code_name']; // replace with your desired columns

        // Retrieve the province with the specified columns
        $province = Province::select($columns)->find($id);
        // Check if the province exists
        if ($province) {
            // Return the province data as a JSON response
            return response()->json([
                'success' => true,
                'data' => $province
            ], 200)->header('Content-Type', "application/json;charset=UTF-8");
        } else {
            // Return an error if the province is not found
            return response()->json([
                'success' => false,
                'message' => 'Province not found'
            ], 404);
        }
    }
    public function getAllDistricts()
    {
        // Specify the columns you want to retrieve
        $columns = ['id', 'full_name', 'code_name'];

        // Retrieve the province with the specified columns
        $districts = District::select($columns)->get();

        // Return provinces as a JSON response
        return response()->json([
            'success' => true,
            'data' => $districts
        ], 200);
    }

    // Get districts by province_code
    public function getDistByPCode($province_code)
    {
         // Validate the province_code
        $validator = validator(['province_code' => $province_code], [
            'province_code' => ['required', new IdValidator('province')],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        // Retrieve the province by the string-based province_code
        $province = Province::where('id', $province_code)->firstOrFail();

        // Retrieve districts for the province
        $districts = $province->districts()->select('id', 'province_code', 'name')->get();

        // Return the districts as a JSON response
        return response()->json($districts);
    }

    // Get wards by district_code
    public function getWardByDCode($dist_code)
    {
         // Validate the district_code
        $validator = validator(['district_code' => $dist_code], [
            'district_code' => ['required', new IdValidator('district')],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        // Retrieve wards by the provided district_code
        $wards = Ward::where('district_code', $dist_code)
        ->select('id', 'full_name', 'code_name', 'district_code')
        ->get();

        // Check if any wards were found
        if ($wards->isEmpty()) {
        return response()->json(['message' => 'No wards found for the provided district code'], 404);
        }

        // Return the wards as a JSON response
        return response()->json($wards);
    }

}
