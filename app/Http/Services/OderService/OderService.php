<?php

namespace App\Http\Services\OderService;

use Illuminate\Support\Str;
use App\Models\Oder;
use App\Models\City;
use App\Models\TransPortFee;
use App\Models\District;
use App\Models\Wards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OderState;

class OderService
{
    public function getAllOder()
    {
        return (Oder::orderBy('created_at', 'DESC')->get());
    }
    public function getAllCity()
    {
        return (City::all());
    }
    public function whereOder($id)
    {
        return (Oder::where('id', $id)->firstOrFail());
    }
    public function updateActive($request, $id)
    {
        $id_oder = $id;
        $active = $request->input('active');
        $activeOderCheck = Oder::find($id_oder);
        if ($request->input('type') == 'check') {
            if ($activeOderCheck->active + 1 == $active && $activeOderCheck->active != 6) {
                return response()
                    ->json([
                        'status' => true
                    ]);
            } else {
                return response()
                    ->json([
                        'status' => false
                    ]);
            }
        } else {
            $userId = Auth::id();
            $content =  $request->input('content');
            $active = $request->input('active');
          //  echo $userId;
            //echo $content;
            //echo $active;
            if($active == 6){
                  $update = Oder::where('id', $id)->first();
                  $update->active = 6;
                  $update->updated_at = date('Y-m-d H:i:s');
                  $update->save();
            }else {
                DB::table('oder')->where('id', '=', $id)->increment('active', 1);
            }
            $insert = new OderState();
                $insert->active = $active;
                $insert->content = $content;
                $insert->user_id = $userId;
                $insert->oder_id = $id_oder;
                $insert->updated_at =  date('Y-m-d H:i:s');
                $insert->created_at =  date('Y-m-d H:i:s');
                $insert->save();
                return response()
                ->json([
                    'status' => 'success',
                    'user_name' => Auth::user()->name,
                    'date' => date('Y-m-d H:i:s')
                ]);

        }
    }
    public function update($request, $id)
    {
        $update = Oder::find($id);
        /*echo "Cột thay đổi ". $request->input('changeColumn') . "<br/>";
        echo 'id đơn hàng = ' . $id . "<br/>";
        echo "Giá trị ". $request->input('value') . "<br/>";*/
        if ($request->input('changeColumn') == 'name') {
            $update->name = $request->input('value');
            $update->updated_at = date('Y-m-d H:i:s');
            $update->save();
        }
        if ($request->input('changeColumn') == 'number') {
            $update->phone_number = $request->input('value');
            $update->updated_at = date('Y-m-d H:i:s');
            $update->save();
        }
        if ($request->input('changeColumn') == 'address-detail') {
            $city_id = $request->input('city_id');
            $district_id = $request->input('district_id');
            $ward_id = $request->input('ward_id');
            $address = $request->input('address_detail');
            $addressDetail = City::where('matp', $city_id)->first()->name . "/" . District::where('maqh',  $district_id)->first()->name . "/" . Wards::where('xaid', $ward_id)->first()->name . "/" . $address;
            $getTransport = TransPortFee::where([
                'city' => City::where('matp', $city_id)->first()->name,
                'district' => District::where('maqh',  $district_id)->first()->name,
                'ward' => Wards::where('xaid', $ward_id)->first()->name,
            ])->first();
            if (is_null($getTransport)) {
                $transportFee = 0;
            } else {
                $transportFee = $getTransport->ship;
            }
            $update->address_detail = $addressDetail;
            $update->transport_fee = $transportFee;
            $update->save();
            return response()
                ->json([
                    'address_detail' => $addressDetail,
                    'transport_fee' => $transportFee,
                ]);
        }
    }
}
