<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\OderService\OderService;
use Illuminate\Support\Facades\Auth;

class OderController extends Controller
{
    protected $oderService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->oderService = new OderService();
    }
    public function index()
    {
        //
        $dataItem = $this->oderService->getAllOder();
        //   dd($data);
        $user = Auth::user();
        $title = 'Danh sách Đơn hàng';
        return (view('admin.oder.list', compact('dataItem', 'user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        //
        echo $id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $dataCity = $this->oderService->getAllCity();

        $dataOder = $this->oderService->whereOder($id);

        $statusOder = ($dataOder->getOderState);
        foreach($statusOder as $key =>$value){

            if($value->active == 6){
                if($key != 5){
                    $statusOder[5] = $statusOder[$key];
                    unset($statusOder[$key]);
                }
                break ;
            }

        }
      //  dd($statusOder);
      //  dd($demo[1]->getUser);
       /* if(!isset($demo[2])){
          dd('trống');
        }*/
        $title = 'Chi tiết Đơn hàng anh "'.$dataOder->name.'"';
        return (view('admin.oder.detail', compact('statusOder','dataOder','dataCity','user', 'title')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataOder = $this->oderService->whereOder($id);


        $user = Auth::user();
        $title = 'Danh sách Đơn hàng anh "'.$dataOder->name.'"';
        return (view('admin.oder.edit', compact('dataOder','user', 'title')));
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

       return  $this->oderService->update($request, $id);
    }
    public function updateActive (Request $request, $id){
        return  $this->oderService->updateActive($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
