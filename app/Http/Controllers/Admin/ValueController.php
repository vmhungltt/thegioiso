<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ValueService\ValueService;
class ValueController extends Controller
{
    protected $serviceValue;
    public function __construct()
    {
       $this->serviceValue = new ValueService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAtribute(Request $request){
        return ($this->serviceValue->getTypes($request));
    }
    public function index()
    {
        //

        $dataItem = $this->serviceValue->getValueAll();
        $dataAtribute = $this->serviceValue->getAtributeAll();


        $user = Auth::user();
        $title = 'Danh sách giá trị thuộc tính của sản phẩm';
        return (view('admin.values.list', compact('dataAtribute','dataItem', 'user', 'title')));
      //  return ()
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();
        $dataAtribute = $this->serviceValue->getAtributeAll();
        $title = 'Thêm giá trị thuộc tính cho sản phẩm';
        return (view('admin.values.add',  compact( 'dataAtribute','user', 'title')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'value' => 'required'
        ], [
            'value.required' => 'Giá trị thuộc tính không được để trống',
        ]);
        return ($this->serviceValue->insert($request));
      //  dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return($this->serviceValue->delete($request));
    }
}
