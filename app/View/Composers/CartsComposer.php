<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\ManageColor;
use App\Models\AccrountCart;
class CartsComposer
{

    public function __construct()
    {
    }
    public function compose(View $view)
    {

        if (Auth::check()) {
            Session::forget('carts');
            $user_id = Auth::id();
            $dataCart = AccrountCart::where('user_id', $user_id)->get();
            $view->with(compact('dataCart'));
        } else {
            $arrayCarts = Session::get('carts');
            if (is_null($arrayCarts)) {
                $dataCart = array();
                $arrayCarts = [0];
            } else {
                $arrayQuery =  array_keys($arrayCarts);
                $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            }
            $view->with(compact('dataCart', 'arrayCarts'));
        }

    }
}
