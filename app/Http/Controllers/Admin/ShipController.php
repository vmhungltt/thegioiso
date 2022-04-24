<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\ShipService\ShipService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Models\Wards;
use App\Models\District;

class ShipController extends Controller
{
    protected $serviceShip;
    public function __construct()
    {
        $this->serviceShip = new ShipService();
    }
    public function getDistrict(Request $request)
    {
        return ($this->serviceShip->getDistrict($request));
    }
    public function getWard(Request $request)
    {
        return ($this->serviceShip->getWard($request));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dataItem = $this->serviceShip->getTransPortFee();
        $user = Auth::user();
        $title = 'Danh sách phí vận chuyển';
        return (view('admin.ship.list', compact('dataItem', 'user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $listCity = $this->serviceShip->getCity();
        $user = Auth::user();
        $title = 'Thêm phí vận chuyển mới';
        return (view('admin.ship.add', compact('listCity', 'user', 'title')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->input('city') == 'null' || !$request->has('district')  || !$request->has('wards')) {
            return back()->with('city', 'Bạn chưa chọn địa chỉ nào!');
        }
        $request->validate([
            'price' => 'required',
        ], [
            'price.required' => 'Bạn chưa nhập phí vận chuyển',
        ]);
        return ($this->serviceShip->insert($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataItem = $this->serviceShip->whereTransPortFee($id);
        /* foreach(City::where('name', $dataItem->city)->first()->getDistrict as $value){
        // echo $value->name . "<br/>";
     }
     foreach(Wards::where('maqh', District::where('name', $dataItem->district)->first()->maqh)->get() as $value){
       //  echo $value->name . "<br/>";
     } */



        $listCity = $this->serviceShip->getCity();
        $listDistrict = City::where('name', $dataItem->city)->first()->getDistrict;
        $listWards = Wards::where('maqh', District::where('name', $dataItem->district)->first()->maqh)->get();
        $user = Auth::user();
        $title = 'Sửa phí vận chuyển';
        return (view('admin.ship.edit', compact('listDistrict', 'listWards', 'dataItem', 'listCity', 'user', 'title')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if (!$request->has('district')  || !$request->has('wards')) {
            return back()->with('city', 'Bạn chưa chọn địa chỉ nào!');
        }
        $request->validate([
            'price' => 'required',
        ], [
            'price.required' => 'Bạn chưa nhập phí vận chuyển',
        ]);
        return ($this->serviceShip->update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $result = $this->serviceShip->delete($request);
        if ($result) {
            return (response()->json([
                'error' => false,
                'messenge' => 'Xóa Thành Công ',
            ]));
        }
    }
}
