<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ManageColor;
use App\Models\LibraryColor;
use App\Http\Services\Color\ColorService;

class ColorController extends Controller
{
    protected $colorService;
    public function __construct()
    {
        $this->colorService = new ColorService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $listItem = Product::all();
        $user = Auth::user();
        $title = 'Danh sách màu sắc sản phẩm';
        return (view('admin.color.list', compact('listItem', 'user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $dataItem = Product::where('id', '=', $id)->firstOrFail();
        $idProduct = $id;
        $user = Auth::user();
        $title = 'Thêm màu sắc sản phẩm ' . $dataItem->name;
        return (view('admin.color.add', compact('idProduct', 'user', 'title')));
        //
        // echo 'hiện thị form';
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
            'price' => 'required',
            'price_sale' => 'required',
            'active' => 'required',
            'thumb' => 'required',
            'library' => 'required',
            'quantity' => 'required'
        ], [

            'name.required' => 'Tên màu sắc không được để trống',
            'price.required' => 'Giá gốc không được để trống',
            'price_sale.required' => 'Giá ưu đãi không được để trống',
            'thumb.required' => 'Chọn ít nhất một ảnh đại diện',
            'quantity.required' => 'Số lượng sản phẩm không được bỏ trống',
            'library.required' => 'Chọn ít nhất một ảnh cho thư viện màu sắc',
        ]);
        return ($this->colorService->insertColor($request));
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

        $name = Product::where('id', '=', $id)->firstOrFail();
        $user = Auth::user();
        $title = 'Chi tiết màu sắc sản phẩm ' .  $name->name;
        $listItem = ManageColor::where('product_id', $id)->get();
        return (view('admin.color.detail', compact('user', 'title', 'listItem')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $name = ManageColor::where('id', '=', $id)->firstOrFail();
        $user = Auth::user();
        $title = 'Sửa "' . $name->name_color . '" cho sản phẩm ' . $name->getProduct->name;
        //$name->name_color;
        //  $listItem = ManageColor::where('id', $id)->firstOrFail();

        return (view('admin.color.edit', compact('name', 'user', 'title')));
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
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'price_sale' => 'required',
            'active' => 'required',
            'quantity' => 'required'
        ], [
            'name.required' => 'Tên màu sắc không được để trống',
            'price.required' => 'Giá gốc không được để trống',
            'price_sale.required' => 'Giá ưu đãi không được để trống',
            'quantity.required' => 'Số lượng sản phẩm không được bỏ trống',
        ]);
        return ($this->colorService->update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showLibrary($id)
    {
       // echo $id;
        $dataItem = ManageColor::where('id', $id)->firstOrFail();
        //  dd($dataItem->getLibraryColor); // lấy ra thư viện
        //dd( $dataItem->name_color);// lấy ra màu sắc sản phẩm đó
       // dd($dataItem->getProduct->name); // lấy ra tên sản phẩm

        // $dataItem = LibraryColor::where('manage_color_id', $id)->get();
        $user = Auth::user();
            $title = 'Thư viện ảnh "'.$dataItem->name_color.'" thuộc sản phẩm "'.$dataItem->getProduct->name.'"';

        return (view('admin.color.library', compact('dataItem','user', 'title')));
    }
    public function destroy(Request $request)
    {
        return ($this->colorService->deleteLibrary($request));
    }
    public function uploadLibrary(Request $request, $id)
    {
        $validated = $request->validate([
            'library' => 'required',
        ], [
            'library.required' => 'Chọn ít nhất một ảnh cho thư viện màu sắc',
        ]);
        return ($this->colorService->uploadLibrary($request, $id));
        //  echo $id;
        // dd($request->all());
    }
}
