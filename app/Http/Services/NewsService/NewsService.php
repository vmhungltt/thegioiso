<?php

namespace App\Http\Services\NewsService;

use Illuminate\Support\Str;
use App\Models\CategoryNews;
use App\Models\Product;
use App\Models\News;
use App\Models\BusinessPlatform;
class NewsService
{
    public function getPlatForm(){
        return (BusinessPlatform::all());
    }
    public function getProductAll()
    {
        return (Product::all());
    }
    public function getCategoryNewsAll()
    {
        return (CategoryNews::all());
    }
    public function getNewsAll(){
        return (News::all());
    }
    public function insert($request)
    {
        if ($request->hasFile('thumb')) {
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/news/', $nameFile);
        }
        $insert = new News();
        $insert->title = $request->input('title');
        $slug = Str::slug($request->input('title'), '-');
        $insert->slug = $slug;
        $insert->category_news_id = $request->input('category_news');
        $insert->description = $request->input('description');
        $insert->product_id = $request->input('product_id');
        $insert->content = $request->input('content');
        $insert->thumb =  $nameFile;
        $insert->updated_at =  date('Y-m-d H:i:s');
        $insert->created_at =  date('Y-m-d H:i:s');
        $insert->save();
        return (redirect()->back()->with('success', 'Đã thêm tin tức ' . $request->input('title')));
    }
    public function where($slug)
    {
        $result  = News::where('slug', '=', $slug)->firstOrFail();
        return ($result);
    }
    public function update($request, $slug)
    {

        $update = News::where('slug', $slug)->firstOrFail();
        if ($request->hasFile('thumb')) {
            $url = 'storage/news/' . $update->thumb;
            unlink($url);
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/news/', $nameFile);
            $update->thumb = $nameFile;
        }
        $update->title = $request->input('title');
        $slug = Str::slug($request->input('title'), '-');
        $update->slug = $slug;
        $update->category_news_id = $request->input('category_news');
        $update->description = $request->input('description');
        $update->product_id = $request->input('product_id');
        $update->content = $request->input('content');
        $update->updated_at =  date('Y-m-d H:i:s');
        $update->save();
        return (redirect('/admin/news/edit/' . $slug)->with('success', 'Cập nhật tin tức "' . $request->input('title') . '" Thành công!'));
    }
    public function delete($request)
    {
        $id = $request->input('id');
        $img = News::where('id', $id)->firstOrFail();
        $url = 'storage/news/' . $img->thumb;
        unlink($url);
        return ($img->delete());
    }
}
