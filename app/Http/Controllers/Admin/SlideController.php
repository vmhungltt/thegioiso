<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\SlideService\SlideService;
class SlideController extends Controller
{
    protected $serviceSlide;
    public function __construct()
    {
       $this->serviceSlide = new SlideService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $dataItem = $this->serviceSlide->getSlidesAll();
        $title = 'Danh sách slide';
        return (view('admin.slide.list', compact('dataItem', 'user', 'title')));
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
        $title = 'Thêm slide mới';
        return (view('admin.slide.add', compact( 'user', 'title')));
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
            'name' => 'required',
            'url' => 'required',
            'thumb' => 'required',
        ], [
            'name.required' => 'Tên slide không được để trống',
            'url.required' => 'Đường dẫn không được để trống',
            'thumb.required' => 'Cần ít nhất một ảnh cho slide',
        ]);
        return ($this->serviceSlide->insert($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataItem = $this->serviceSlide->where($id);
        $user = Auth::user();
        $title = 'Chỉnh sửa slide ' .$dataItem->name;
        return (view('admin.slide.edit', compact( 'dataItem','user', 'title')));
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
        $validated = $request->validate([
            'name' => 'required',
            'url' => 'required',
        ], [
            'name.required' => 'Tên slide không được để trống',
            'url.required' => 'Đường dẫn không được để trống',
        ]);
       return ($this->serviceSlide->update($request, $id));
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
        $result = $this->serviceSlide->delete($request);
        if ($result) {
            return (response()->json([
                'error' => false,
                'messenge' => 'Xóa Thành Công ',
            ]));
        }
    }
}
