<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\NewsService\NewsService;
class NewsController extends Controller
{
    protected $newsService;
    public function __construct()
    {
        $this->newsService =  new NewsService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
      $dataItem = $this->newsService->getNewsAll();
     // dd($dataItem);
        $user = Auth::user();
        $title = 'Danh sách tin tức';
        return (view('admin.news.list', compact('dataItem','user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $listCategory = $this->newsService->getCategoryNewsAll();
        $listProduct = $this->newsService->getProductAll();
        $user = Auth::user();
        $title = 'Thêm tin tức mới';
        return (view('admin.news.add', compact('listProduct','listCategory', 'user', 'title')));
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
            'title' => 'required',
            'content' => 'required',
            'thumb' => 'required',
            'description' => 'required',
        ], [
            'description.required' => 'Mô tả ngắn tin tức không được để trống',
            'title.required' => 'Tiêu đề không được để trống',
            'content.required' => 'Nội dung không được để trống',
            'thumb.required' => 'Lựa chọn ít nhất một ảnh cho tin tức',
        ]);
       return ($this->newsService->insert($request));
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $item = $this->newsService->where($slug);
        $listCategory = $this->newsService->getCategoryNewsAll();
        $listProduct = $this->newsService->getProductAll();
        $user = Auth::user();
        $title = 'Thêm tin tức mới';
        return (view('admin.news.edit', compact('item','listProduct','listCategory', 'user', 'title')));
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
            'title' => 'required',
            'content' => 'required',
            'description' => 'required',
        ], [
            'description.required' => 'Mô tả ngắn tin tức không được để trống',
            'title.required' => 'Tiêu đề không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);
        return ($this->newsService->update($request, $slug));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return ($this->newsService->delete($request));
    }
}
