<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CartService\CartService;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
    protected $cartService;
    public function __construct()
    {
        $this->cartService = new CartService();
    }
    public function getTransPortFee(Request $request){
        return ($this->cartService->getTransPortFee($request));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authOTP (Request $request){

     return ($this->cartService->authOTP($request));
    }
    public function index()
    {
       if(Auth::check()){
           if(Auth::user()->detail_address == '' || Auth::user()->detail_address == null){
            return (redirect('set-infor')->with('error', 'Bạn cần phải đăng ký thông tin thanh toán trước!'));
           }
       }
        //
        return ($this->cartService->selectDetailCart());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return ($this->cartService->insert($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return ($this->cartService->getTotal());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //showCart
        return ($this->cartService->showCart());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function handleOTP(Request $request)
    {
        $objectForm = $request->input('objectForm');
       // $district = $wards = $addressDetail = $name = $phoneNumber = $email = $content = '';
        foreach ($objectForm as $key => $value) {
            if ($value["name"] == 'email') {
                $email = $value["value"];
            }
        }
      //  echo $email;
      return ($this->cartService->handleOtpShoping($email, $objectForm));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return ($this->cartService->updateDetailCart($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return ($this->cartService->deleteCart($request));
    }
    public function deleteCartDetail(Request $request)
    {
        return ($this->cartService->deleteDetailCart($request));
    }
}
