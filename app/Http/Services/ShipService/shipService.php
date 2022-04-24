<?php

namespace App\Http\Services\ShipService;

use Illuminate\Support\Str;
use App\Models\Brands;
use App\Models\Slide;
use App\Models\City;
use App\Models\District;
use App\Models\Wards;
use App\Models\TransPortFee;
class ShipService
{
    public function getDistrictUser($request)
    {
        $city_id = $request->input('city_id');
        $dataItem = City::where('matp', $city_id)->first();
        return (view('post.users.outputDistrict', compact('dataItem')));
    }
    public function getWardsUser($request)
    {
        $district_id = $request->input('district_id');
        $dataItem = Wards::where('maqh',   $district_id)->get();
        return (view('post.users.outputWard', compact('dataItem')));
    }
    public function getWard($request)
    {
        $district_id = $request->input('district_id');
        $dataItem = Wards::where('maqh',   $district_id)->get();
        return (view('admin.ship.outputWard', compact('dataItem')));
    }
    public function getWardPost($request)
    {
        $district_id = $request->input('district_id');
        $dataItem = Wards::where('maqh',   $district_id)->get();
        return (view('post.address.outputWards', compact('dataItem')));
    }
    public function getCity()
    {
        return (City::all());
    }
    public function getWardAll(){
        return (Wards::all());
    }
    public function getDistrictAll(){
        return (District::all());
    }
    public function getTransPortFee()
    {
        return (TransPortFee::all());
    }
    public function whereTransPortFee($id){
        return (TransPortFee::where('id', $id)->firstOrFail());
    }
    public function getDistrict($request)
    {
        $city_id = $request->input('city_id');

        $dataItem = City::where('matp', $city_id)->first();
        return (view('admin.ship.outputDistrict', compact('dataItem')));
    }
    public function getDistrictPost($request)
    {
        $city_id = $request->input('city_id');

        $dataItem = City::where('matp', $city_id)->first();
        return (view('post.address.outputDistrict', compact('dataItem')));
    }
    public function insert($request)
    {

       $city = City::where('matp', $request->input('city'))->first();
       $district = District::where('maqh', $request->input('district'))->first();
       $ward =  Wards::where('xaid', $request->input('wards'))->first();
       $price = $request->input('price');
        $item = new TransPortFee();
        $item->city = $city->name;
        $item->district = $district->name;
        $item->ward = $ward->name;
        $item->ship = $price;
        $item->created_at = date('Y-m-d H:i:s');
        $item->updated_at =date('Y-m-d H:i:s');
        $item->save();
        return (redirect()->back()->with('success', 'Đã thêm phí vận chuyển '));
    }
    public function getSlidesAll()
    {
        return (Slide::all());
    }
    public function where($id)
    {
        return (Slide::where('id', $id)->firstOrFail());
    }
    public function update($request, $id)
    {
        $item = TransPortFee::where('id', $id)->firstOrFail();
        $city = City::where('matp', $request->input('city'))->first();
        $district = District::where('maqh', $request->input('district'))->first();
        $ward =  Wards::where('xaid', $request->input('wards'))->first();
        $price = $request->input('price');
        $item->city = $city->name;
        $item->district = $district->name;
        $item->ward = $ward->name;
        $item->ship = $price;
        $item->updated_at =date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/ship/edit/' . $id)->with('success', 'Cập nhật phí vận chuyển Thành công!'));
    }
    public function delete($request)
    {
        $id = $request->input('id');
        $item = TransPortFee::where('id', $id)->firstOrFail();
        return ($item->delete());
    }
}
