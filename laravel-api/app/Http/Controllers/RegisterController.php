<?php
namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Rules\IdValidator;
use App\Services\RegistrationService;

class RegisterController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }
    public function getAllProvinces()
    {
        // Retrieve the province with the specified columns
        $provinces = Province::getProvinces();

        // Return provinces as a JSON response
        return response()->json([
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

        $province = Province::getProvinces();
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

        // Retrieve districts for the province
        $districts = District::getDistrictsByProvinceCode($province_code);

        // Return the districts as a JSON response
        return response()->json([
            'data' => $districts
        ], 200)->header('Content-Type', "application/json;charset=UTF-8");
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
        $wards = Ward::getWardsByDistrictCode($dist_code);

        // Check if any wards were found
        if ($wards->isEmpty()) {
        return response()->json(['message' => 'No wards found for the provided district code'], 404);
        }

        // Return the wards as a JSON response
        return response()->json([
            'data' => $wards
        ], 200)->header('Content-Type', "application/json;charset=UTF-8");
    }

    public function register(Request $request)
    {
        $data = $request->only([
            'email', 'password', 'firstName', 'lastName', 'birthday',
            'genderId', 'street', 'wardId', 'districtId', 'provinceId'
        ]);

        $result = $this->registrationService->register($data);

        if ($result['success']) {
            return response()->json(['message' => 'Registration successful'], 200)
                ->header('Content-Type', "application/json;charset=UTF-8");
        }

        return response()->json(['message' => 'Registration successful'], 200)->header('Content-Type', "application/json;charset=UTF-8");
    }
}
