<?php

namespace App\Http\Services\CategoryNewsService;

use Illuminate\Support\Str;
use App\Models\CategoryNews;
use App\Helper\Helper;

class CategoryNewsService
{
    public function insert($request)
    {
        $insert = new CategoryNews();
        $insert->name = $request->name;
        $insert->slug = Str::slug($request->name, '-');
        $insert->created_at = date('Y-m-d H:i:s');
        $insert->updated_at = date('Y-m-d H:i:s');
        $insert->save();
        return (redirect()->back()->with('success', 'Đã thêm danh mục ' . $request->name));
    }
    public function getCategoryNewsAll()
    {
        return (CategoryNews::all());
    }
    public function delete($request)
    {
        $id = $request->input('id');
        return (CategoryNews::where('id', $id)->delete());
    }
    public function where($slug)
    {
        $result  = CategoryNews::where('slug', '=', $slug)->firstOrFail();
        return ($result);
    }
    public function update($request, $slug)
    {

        $item = CategoryNews::where('slug', $slug)->first();
        $name = $request->input('name');
        $slug = Str::slug($name, '-');
        $item->name = $name;
        $item->slug = $slug;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/category-news/edit/' . $slug)->with('success', 'Cập nhật danh mục "' . $name . '" Thành công!'));
    }
}
