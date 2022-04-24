<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessPlatform;
use App\Http\Services\CategoryService\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct()
    {
        $this->categoryService =  new CategoryService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $user = Auth::user();
        $listItem = $this->categoryService->getCategoryAll();
        $title = 'Danh sách danh mục';
        return (view('admin.category.list', compact('listItem', 'user', 'title')));
        //
        //  echo 'danh sách danh mục ';
        //return ( $this->categoryService->getCategoryAll());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listBusinessPlatform = BusinessPlatform::all();
        $listCategoryParent =  $this->categoryService->getCategoryParent();
        $user = Auth::user();
        $title = 'Thêm danh mục mới';
        return (view('admin.category.add', compact('listBusinessPlatform','listCategoryParent', 'user', 'title')));
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
            'description' => 'required',
            'content' => 'required'
        ], [
            'name.required' => 'Tên danh mục không được để trống',
            'description.required' => 'Mô tả không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);
        return ($this->categoryService->insert($request));
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
        $listBusinessPlatform = BusinessPlatform::all();
        $dataItem = $this->categoryService->where($slug);
        $listCategoryParent =  $this->categoryService->getCategoryParent();
        $user = Auth::user();
        $title = 'Sửa danh mục ' . $dataItem->name;

        return (view('admin.category.edit', compact('listBusinessPlatform','listCategoryParent', 'user', 'title', 'dataItem')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        //
        echo $slug;
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
            'description' => 'required',
            'content' => 'required'
        ], [
            'name.required' => 'Tên danh mục không được để trống',
            'description.required' => 'Mô tả không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);
       return ($this->categoryService->update($request, $slug));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $result = $this->categoryService->delete($request);
        if ($result) {
            return (response()->json([
                'error' => false,
                'messenge' => 'Xóa Thành Công ',
            ]));
        }
    }
}
