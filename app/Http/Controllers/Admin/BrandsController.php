<?php

namespace App\Http\Controllers\Admin;
use App\Http\Services\BrandsService\BrandsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BrandsController extends Controller
{
    protected $serviceBrands;
    public function __construct()
    {
       $this->serviceBrands = new BrandsService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $listItem = $this->serviceBrands->getBrandsAll();
        $title = 'Danh sách thương hiệu';
        return (view('admin.brands.list', compact('listItem', 'user', 'title')));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $title = 'Thêm thương hiệu mới';
        return (view('admin.brands.add', compact('user', 'title')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Tên thương hiệu không được để trống',
        ]);
        return ($this->serviceBrands->insert($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataItem = $this->serviceBrands->where($id);
        $user = Auth::user();
        $title = 'Sửa thương hiệu sản phẩm ' .$dataItem->name;

        return (view('admin.brands.edit', compact('user', 'title', 'dataItem')));
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
        $validated = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Tên thương hiệu không được để trống',
        ]);
        return ($this->serviceBrands->update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $result = $this->serviceBrands->delete($request);
        if ($result) {
            return (response()->json([
                'error' => false,
                'messenge' => 'Xóa Thành Công ',
            ]));
        }
    }
}
