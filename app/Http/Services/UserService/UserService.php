<?php

namespace App\Http\Services\UserService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Brands;
use App\Models\User;

class UserService
{
    public function getAllUser(){
       return (User::orderBy('created_at', 'DESC')->get());
    }
    public function setInfor($request)
    {
        $update = User::find(Auth::id());
     //  dd($request->all());
       /*
        "_token" => "zcn0KLqk5AitsAguslU5DDy4Pgo05YSWK9sBj9yO"
  "city" => "08"
  "district" => "070"
  "wards" => "02200"
  "number" => "3"
  "address-detail" => "3"
       */
      $update->city_id = $request->input('city');
      $update->district_id = $request->input('district');
      $update->wards_id = $request->input('wards');
      $update->phone_number = $request->input('number');
      $update->detail_address= $request->input('address-detail');
      $update->updated_at = date('Y-m-d H:i:s');
      $update->save();
      return (redirect('/cart'));
    }
}
