<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\FooterService\FooterService;
class ConfigController extends Controller
{
    protected $footerService;
    public function __construct(){
        $this->footerService = new FooterService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $title = 'Danh sách footer website';
      $dataItem = $this->footerService->getList();
      return (view('admin.footer.list', compact('dataItem','user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = Auth::user();
        $title = 'Thêm Footer mới';
        return (view('admin.footer.add', compact('user', 'title')));


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
        $request->validate([
            'name' => 'required|unique:footer',
            'content' => 'required',
        ], [
            'name.required' => '* Tên không được để trống!',
            'content.required' => '* Nội dung không được để trống!',
            'name.unique' => '* Tên footer đã tồn tại!',
        ]);
   return ($this->footerService->insert($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $dataItem = $this->footerService->whereSlug($slug);

        $user = Auth::user();
        $title = 'Chỉnh sửa nội dung "'.$dataItem->name.'"';
       return (view('admin.footer.edit', compact('dataItem','user', 'title')));
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
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ], [
            'name.required' => '* Tên không được để trống!',
            'content.required' => '* Nội dung không được để trống!',
        ]);
       return ($this->footerService->update($request, $slug));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       return ($this->footerService->destroy($request));
    }
}
