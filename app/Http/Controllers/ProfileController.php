<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{

    public function CreateProfile(Request $request): JsonResponse
    {
        //  middleware merged this
        $user_id = (int) $request->input('user_id');

        if (!$user_id) {
            return ResponseHelper::Out('fail', 'Unauthorized: user_id missing', 401);
        }

        // only allow profile fields (safe)
        $payload = $request->only([
            'cus_name','cus_add','cus_city','cus_state','cus_postcode','cus_country','cus_phone','cus_fax',
            'ship_name','ship_add','ship_city','ship_state','ship_postcode','ship_country','ship_phone',
        ]);

        foreach ($payload as $k => $v) {
            if (is_null($v)) $payload[$k] = "";
        }

        $data = CustomerProfile::updateOrCreate(
            ['user_id' => $user_id],
            $payload
        );

        return ResponseHelper::Out('success', $data, 200);
    }
}
