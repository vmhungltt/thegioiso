<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\PlatFormService\PlatFormService;
class PlatFormController extends Controller
{
    protected $platFormService;
    public function __construct()
    {
        $this->platFormService =  new PlatFormService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $listItem = $this->platFormService->getPlatFormAll();
        $title = 'Danh sách nền tảng';
        return (view('admin.platform.list', compact('listItem', 'user', 'title')));
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
        $title = 'Thêm nền tảng kinh doanh  mới';
        return (view('admin.platform.add', compact( 'user', 'title')));
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
            'name' => 'required|unique:business-platform,name',
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại',
            'name.required' => 'Tên danh mục không được để trống',
        ]);
        return ($this->platFormService->insert($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $dataItem = $this->platFormService->where($slug);
        $user = Auth::user();
        $title = 'Sửa nền tảng ' .$dataItem->name;

        return (view('admin.platform.edit', compact('user', 'title', 'dataItem')));
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
    public function update(Request $request, $slug)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Tên danh mục không được để trống',
        ]);
       return ($this->platFormService->update($request, $slug));
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
        $result = $this->platFormService->delete($request);
        if ($result) {
            return (response()->json([
                'error' => false,
                'messenge' => 'Xóa Thành Công ',
            ]));
        }
    }
}
