<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\CategoryNewsService\CategoryNewsService;
class CategoryNewsController extends Controller
{
   protected $serviceCategoryNews ;
    public function __construct()
    {
        $this->serviceCategoryNews = new CategoryNewsService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataItem = $this->serviceCategoryNews->getCategoryNewsAll();

        $user = Auth::user();
        $title = 'Danh sách danh mục tin tức';
        return (view('admin.categoryNews.list', compact('dataItem', 'user', 'title')));
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
        $title = 'Thêm danh mục tin tức';
        return (view('admin.categoryNews.add', compact( 'user', 'title')));
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
            'name' => 'required|unique:category_news,name',
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại',
            'name.required' => 'Tên danh mục không được để trống',
        ]);
        return ($this->serviceCategoryNews->insert($request));
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
        $dataItem = $this->serviceCategoryNews->where($slug);
      //  dd($dataItem);
        $user = Auth::user();
        $title = 'Sửa danh mục "'.$dataItem->name.'"';
        return (view('admin.categoryNews.edit', compact('dataItem', 'user', 'title')));
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
        return($this->serviceCategoryNews->update($request, $slug));
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
        return ($this->serviceCategoryNews->delete($request));
    }
}
