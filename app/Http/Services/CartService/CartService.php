<?php

namespace App\Http\Services\CartService;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\ManageColor;
use App\Models\AccrountCart;
use App\Models\City;
use App\Models\District;
use App\Models\Wards;
use App\Models\TransPortFee;
use Illuminate\Support\Facades\Cookie;


class CartService
{
    public function getTransPortFee($request)
    {
        /*
         city_id: 02
district_id: 024
wards_id: 00688
         */
        $cityId = $request->input('city_id');
        $districtId = $request->input('district_id');
        $wardsId = $request->input('wards_id');
        $data = TransPortFee::where([
            'city' => City::where('matp', $cityId)->select('name')->first()->name,
            'district' => District::where('maqh', $districtId)->select('name')->first()->name,
            'ward' => Wards::where('xaid',  $wardsId)->select('name')->first()->name,
        ])->first();
        // City::where('matp', $cityId)->select('name')->first()->name;
        if (is_null($data)) {
            return response()->json(['transportfee' => 0]);
        } else {
            return response()->json(['transportfee' => $data->ship]);
        }
    }
    public function authOTP($request)
    {
        $OTP = Cookie::get('otp-auth');
        $input = $request->input('codeOTP');
        if ($OTP == null) {
            return response()->json(['status' => 'error', 'messenger' => '* Mã OTP đã hết hạn']);
        }

        if ((string)$OTP == (string)$input) {
            $information = array();
            if (Auth::check()) {
                foreach ($request->input('objectForm') as $key => $value) {
                    if ($value["name"] == 'content') {
                        $information['content'] = $value["value"];
                    }
                }
                $information['email'] =  Auth::user()->email;
                $information['city'] =   City::where('matp', Auth::user()->city_id)->first();
                $information['district'] = District::where('maqh', Auth::user()->district_id)->first();
                $information['wards'] = Wards::where('xaid', Auth::user()->wards_id)->first();
                $information['address-detail'] = Auth::user()->detail_address;
                $information['name'] =  Auth::user()->name;
                $information['number']  = Auth::user()->phone_number;
            } else {
                foreach ($request->input('objectForm') as $key => $value) {
                    if ($value["name"] == 'email') {
                        $information['email'] = $value["value"];
                    }
                    if ($value["name"] == 'city') {
                        $information['city'] = City::where('matp', $value["value"])->first();
                    }
                    if ($value["name"] == 'district') {
                        $information['district'] = District::where('maqh', $value["value"])->first();
                    }
                    if ($value["name"] == 'wards') {
                        $information['wards'] = Wards::where('xaid', $value["value"])->first();
                    }
                    if ($value["name"] == 'address-detail') {
                        $information['address-detail'] = $value["value"];
                    }
                    if ($value["name"] == 'name') {
                        $information['name'] = $value["value"];
                    }
                    if ($value["name"] == 'number') {
                        $information['number'] = $value["value"];
                    }
                    if ($value["name"] == 'content') {
                        $information['content'] = $value["value"];
                    }
                }
            }


            $details = [
                'title' => 'Xác thực đơn hàng thành công',
                'body' => 'Chi tiết đơn hàng',
            ];

            Mail::to($information['email'])->send(new \App\Mail\OderSuccess($details, $information));
            return response()->json(['status' => 'success',]);
        } else {
            return response()->json(['status' => 'error', 'messenger' => '* Mã OTP không chính xác']);
        }
    }
    public function handleOtpShoping($email, $infor)
    {

        $information = array();
        if (Auth::check()) {
            $email = Auth::user()->email;
            foreach ($infor as $key => $value) {
                if ($value["name"] == 'content') {
                    $information['content'] = $value["value"];
                }
            }
            $information['email'] =  Auth::user()->email;
            $information['city'] =   City::where('matp', Auth::user()->city_id)->first();
            $information['district'] = District::where('maqh', Auth::user()->district_id)->first();
            $information['wards'] = Wards::where('xaid', Auth::user()->wards_id)->first();
            $information['address-detail'] = Auth::user()->detail_address;
            $information['name'] =  Auth::user()->name;
            $information['number']  = Auth::user()->phone_number;
        } else {
            foreach ($infor as $key => $value) {
                if ($value["name"] == 'email') {
                    $information['email'] = $value["value"];
                }
                if ($value["name"] == 'city') {
                    $information['city'] = City::where('matp', $value["value"])->first();
                }
                if ($value["name"] == 'district') {
                    $information['district'] = District::where('maqh', $value["value"])->first();
                }
                if ($value["name"] == 'wards') {
                    $information['wards'] = Wards::where('xaid', $value["value"])->first();
                }
                if ($value["name"] == 'address-detail') {
                    $information['address-detail'] = $value["value"];
                }
                if ($value["name"] == 'name') {
                    $information['name'] = $value["value"];
                }
                if ($value["name"] == 'number') {
                    $information['number'] = $value["value"];
                }
                if ($value["name"] == 'content') {
                    $information['content'] = $value["value"];
                }
            }
        }

        $OTPRandom  =   rand(10000000, 99999999);
        Cookie::queue('otp-auth', $OTPRandom, 3);
        $details = [
            'title' => 'Xác thực đơn hàng của bạn được gửi từ hệ thống',
            'body' => 'Nhập mã OTP bao gồm 8 chữ số bên dưới vào biểu mẫu xác thực',
            'otp' =>  $OTPRandom
        ];

        Mail::to($email)->send(new \App\Mail\HandleOTP($details));

        if (Auth::check()) {
            $arrayQuery = array();
            $arrayQuantity = array();
            $id = Auth::id();
            $data = AccrountCart::where('user_id', $id)->select('color_id', 'quantity')->get();
            foreach ($data as $value) {
                $arrayQuery[] = $value->color_id;
                $arrayQuantity[$value->color_id] = $value->quantity;
            }
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputOderDetail', compact('information', 'dataCart', 'arrayQuantity')));
        } else {
            $checkCarts = Session::get('carts');
            if (is_null($checkCarts)) {
                echo 'chưa tồn tại';
            } else {
                $arrayQuantity = array();
                $arrayQuantity = $checkCarts;
                $arrayQuery =  array_keys($checkCarts);
                $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
                return (view('post.cart.outputOderDetail', compact('information', 'dataCart', 'arrayQuantity')));
            }
        }
    }
    public function getTotal()
    {
        if (Auth::check()) {
            //  $id = Auth::id();
        } else {
            $checkCarts = Session::get('carts');
            $total = 0;
            foreach ($checkCarts as $key => $value) {
                $total = $total + $checkCarts[$key];
            }
            return (view('post.cart.outputTotal', compact('total')));
        }
    }
    public function selectDetailCart()
    {
        $listCity = City::all();
        if (Auth::check()) {
            $arrayQuery = array();
            $arrayQuantity = array();
            $id = Auth::id();
            $data = AccrountCart::where('user_id', $id)->select('color_id', 'quantity')->get();
            foreach ($data as $value) {
                $arrayQuery[] = $value->color_id;
                $arrayQuantity[$value->color_id] = $value->quantity;
            }
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart', compact('listCity', 'dataCart', 'arrayQuantity')));
        } else {
            $checkCarts = Session::get('carts');
            if (is_null($checkCarts)) {
                $dataCart = array();
                $arrayQuantity = array();
                return (view('post.cart', compact('listCity', 'dataCart', 'arrayQuantity')));
            } else {
                $arrayQuantity = array();
                $arrayQuantity = $checkCarts;
                $arrayQuery =  array_keys($checkCarts);
                $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
                return (view('post.cart', compact('listCity', 'dataCart', 'arrayQuantity')));

                //  foreach( $checkCarts as $key => $value){

                //  }
                // dd($checkCarts);
                //
            }
        }
    }
    public function insert($request)
    {
        /*   echo 'id màu sắn : '. $request->input('color_id');
       echo "<br/>". "số lượng mặc định :" . $request->input('quantity');
       */
        $color_id =  (int)$request->input('color_id');
        $quantity = (int)$request->input('quantity');
        if (Auth::check()) {
            Session::forget('carts');
            $user_id = Auth::id();
            $color_id =  (int)$request->input('color_id');
            $quantity = (int)$request->input('quantity');
            $data = AccrountCart::where('user_id', $user_id)->where('color_id', $color_id)->first();
            if ($data) {
                $quantity =  $quantity + $data->quantity;
            }
            $insert = AccrountCart::updateOrCreate(
                ['user_id' => $user_id, 'color_id' => $color_id],
                ['user_id' => $user_id, 'color_id' => $color_id, 'quantity' => $quantity, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );
            $dataCart = AccrountCart::where('user_id', $user_id)->get();
            return (view('post.cart.outputCartAccrount', compact('dataCart')));
        } else {
            //  Session::forget('carts');
            $checkCarts = Session::get('carts');
            if (is_null($checkCarts)) {
                Session::put('carts', [
                    $color_id =>  $quantity
                ]);
            } else {
                $addQuantity = Arr::exists($checkCarts, $color_id);
                if ($addQuantity == true) {
                    $checkCarts[$color_id] = $checkCarts[$color_id] + $quantity;
                    Session::put(
                        'carts',
                        $checkCarts
                    );
                } else {
                    $checkCarts[$color_id] = $quantity;
                    Session::put(
                        'carts',
                        $checkCarts
                    );
                }
            }
            $arrayCarts = Session::get('carts');
            $arrayQuery =  array_keys($arrayCarts);
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            /*foreach($data as $value){
             echo $value->getProduct->name . "<br/>";
         }*/
            return (view('post.cart.outputCart', compact('dataCart', 'arrayCarts')));
        }
    }
    public function deleteCart($request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $id = $request->input('index');
            $delete = AccrountCart::where([
                'user_id' => $user_id,
                'color_id' => $id
            ])->delete();
            $dataCart = AccrountCart::where('user_id', $user_id)->get();

            return (view('post.cart.outputCartAccrount', compact('dataCart')));
        } else {
            $index = $request->input('index');
            $arrayCarts = Session::get('carts');
            unset($arrayCarts[$index]);
            Session::put(
                'carts',
                $arrayCarts
            );
            $arrayQuery =  array_keys($arrayCarts);
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputCart', compact('dataCart', 'arrayCarts')));
        }
    }
    public function updateDetailCart($request)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $color_id = $request->input('color_id');
            $quantity = $request->input('quantity');
            $update =  AccrountCart::where([
                'user_id' => $id,
                'color_id' => $color_id
            ])->first();
            $update->quantity = $quantity;
            $update->save();
            //////////
            $arrayQuery = array();
            $arrayQuantity = array();
            $data = AccrountCart::where('user_id', $id)->select('color_id', 'quantity')->get();
            foreach ($data as $value) {
                $arrayQuery[] = $value->color_id;
                $arrayQuantity[$value->color_id] = $value->quantity;
            }
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputDetailCart', compact('dataCart', 'arrayQuantity')));
        } else {
            $color_id = $request->input('color_id');
            $quantity = $request->input('quantity');
            $checkCarts = Session::get('carts');
            $checkCarts[$color_id] =  $quantity;
            Session::put(
                'carts',
                $checkCarts
            );
            $arrayQuantity = array();
            $arrayQuantity = $checkCarts;
            $arrayQuery =  array_keys($checkCarts);
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputDetailCart', compact('dataCart', 'arrayQuantity')));
        }
    }
    public function deleteDetailCart($request)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $index = $request->input('index');
            $delete = AccrountCart::where([
                'user_id' => $id,
                'color_id' => $index
            ])->delete();

            $arrayQuery = array();
            $arrayQuantity = array();
            $data = AccrountCart::where('user_id', $id)->select('color_id', 'quantity')->get();
            foreach ($data as $value) {
                $arrayQuery[] = $value->color_id;
                $arrayQuantity[$value->color_id] = $value->quantity;
            }
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputDetailCart', compact('dataCart', 'arrayQuantity')));
        } else {
            $index = $request->input('index');
            $arrayCarts = Session::get('carts');
            unset($arrayCarts[$index]);
            Session::put(
                'carts',
                $arrayCarts
            );
            $checkCarts = Session::get('carts');
            $arrayQuantity = array();
            $arrayQuantity = $checkCarts;
            $arrayQuery =  array_keys($checkCarts);
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputDetailCart', compact('dataCart', 'arrayQuantity')));
        }
    }
    public function showCart()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $arrayQuery = array();
            $arrayCarts = array();
            $data = AccrountCart::where('user_id', $id)->select('color_id', 'quantity')->get();
            foreach ($data as $value) {
                $arrayQuery[] = $value->color_id;
                $arrayCarts[$value->color_id] = $value->quantity;
            }
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            return (view('post.cart.outputCart', compact('dataCart', 'arrayCarts')));
        } else {
            $arrayCarts = Session::get('carts');

            if (is_null($arrayCarts)) {
                $arrayQuery = array();
                $dataCart = array();
            } else {
                $arrayQuery =  array_keys($arrayCarts);
                $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            }

            return (view('post.cart.outputCart', compact('dataCart', 'arrayCarts')));
        }
    }
}
