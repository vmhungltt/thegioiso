<?php

namespace App\Mail;

use Illuminate\Support\Facades\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Models\ManageColor;
use App\Models\AccrountCart;
use App\Models\Oder;
use App\Models\OderDetail;
use App\Models\TransPortFee;

class OderSuccess extends Mailable
{
    use Queueable, SerializesModels;
    public $details, $infor;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $information)
    {
        //
        $this->details = $details;
        $this->infor = $information;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (Auth::check()) {
            $insertOder = new Oder();
            $dataInfor = $this->infor;
            $arrayQuery = array();
            $arrayQuantity = array();
            $total = 0;
            $id = Auth::id();
            $data = AccrountCart::where('user_id', $id)->select('color_id', 'quantity')->get();
            foreach ($data as $value) {
                $total = $total + ($value->getColor->price_sale * $value->quantity);
                $arrayQuery[] = $value->color_id;
                $arrayQuantity[$value->color_id] = $value->quantity;
            }
            $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
            $disCountCode = 0;
            $TransPortFee = 0;
            $dataTransPort = TransPortFee::where([
                'city' => $dataInfor['city']->name,
                'district' => $dataInfor['district']->name,
                'ward' => $dataInfor['wards']->name,
            ])->first();
            if (!is_null($dataTransPort)) {
                $TransPortFee =  $dataTransPort->ship;
            }

            $insertOder->user_id = $id;
            $insertOder->transport_fee = $TransPortFee;
            $insertOder->discount_code = $disCountCode;
            $insertOder->note = $dataInfor['content'];
            $insertOder->name = $dataInfor['name'];
            $insertOder->phone_number = $dataInfor['number'];
            $insertOder->address_detail = $dataInfor['city']->name . '/' .  $dataInfor['district']->name . '/' . $dataInfor['wards']->name . '/' . $dataInfor['address-detail'];
            $insertOder->total =  $total;
            $insertOder->active = 0;
            $insertOder->created_at = date('Y-m-d H:i:s');
            $insertOder->updated_at = date('Y-m-d H:i:s');
            $insertOder->save();
            /*  end insert to database with name oder*/

            $oder_id = $insertOder->id;
            foreach ($dataCart as $value) {
                $insertOderDetail = new OderDetail();
                $insertOderDetail->oder_id = $oder_id;
                $insertOderDetail->color_id = $value->id;
                $insertOderDetail->total = $value->price_sale;
                $insertOderDetail->quantity = $arrayQuantity[$value->id];
                $insertOderDetail->created_at = date('Y-m-d H:i:s');
                $insertOderDetail->updated_at = date('Y-m-d H:i:s');
                $insertOderDetail->save();
            }
            $deleteCart = AccrountCart::where('user_id', $id)->delete();
            return $this->subject('Chi tiết đơn hàng')
                ->view('post.oder.oderMail', compact('dataCart', 'arrayQuantity', 'dataInfor'));
        } else {
            $checkCarts = Session::get('carts');
            if (is_null($checkCarts)) {
                echo 'chưa tồn tại';
            } else {
                $dataInfor = $this->infor;
                $arrayQuantity = array();
                $arrayQuantity = $checkCarts;
                $arrayQuery =  array_keys($checkCarts);
                $dataCart = ManageColor::whereIn('id', $arrayQuery)->get();
                /*  Start insert to database with name oder*/
                $insertOder = new Oder();


                $total = 0;
                $user_id = 0;
                $disCountCode = 0;
                $TransPortFee = 0;
                $dataTransPort = TransPortFee::where([
                    'city' => $dataInfor['city']->name,
                    'district' => $dataInfor['district']->name,
                    'ward' => $dataInfor['wards']->name,
                ])->first();
                if (!is_null($dataTransPort)) {
                    $TransPortFee =  $dataTransPort->ship;
                }
                foreach ($dataCart as $value) {
                    $total = $total + ($value->price_sale * $arrayQuantity[$value->id]);
                }
                $insertOder->user_id = $user_id;
                $insertOder->transport_fee = $TransPortFee;
                $insertOder->discount_code = $disCountCode;
                $insertOder->note = $dataInfor['content'];
                $insertOder->name = $dataInfor['name'];
                $insertOder->phone_number = $dataInfor['number'];
                $insertOder->address_detail = $dataInfor['city']->name . '/' .  $dataInfor['district']->name . '/' . $dataInfor['wards']->name . '/' . $dataInfor['address-detail'];
                $insertOder->total =  $total;
                $insertOder->active = 0;
                $insertOder->created_at = date('Y-m-d H:i:s');
                $insertOder->updated_at = date('Y-m-d H:i:s');
                $insertOder->save();
                /*  end insert to database with name oder*/

                $oder_id = $insertOder->id;


                foreach ($dataCart as $value) {
                    $insertOderDetail = new OderDetail();
                    $insertOderDetail->oder_id = $oder_id;
                    $insertOderDetail->color_id = $value->id;
                    $insertOderDetail->total = $value->price_sale;
                    $insertOderDetail->quantity = $arrayQuantity[$value->id];
                    $insertOderDetail->created_at = date('Y-m-d H:i:s');
                    $insertOderDetail->updated_at = date('Y-m-d H:i:s');
                    $insertOderDetail->save();
                }
                Session::forget('carts');

                return $this->subject('Chi tiết đơn hàng')
                    ->view('post.oder.oderMail', compact('dataCart', 'arrayQuantity', 'dataInfor'));
            }
        }
    }
}
