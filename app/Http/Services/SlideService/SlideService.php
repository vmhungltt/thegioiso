<?php

namespace App\Http\Services\SlideService;

use Illuminate\Support\Str;
use App\Models\Brands;
use App\Models\Slide;

class SlideService
{
    public function insert($request)
    {
        $name = $request->input('name');
        $url = $request->input('url');
        if ($request->hasFile('thumb')) {
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/products/', $nameFile);
        }
        $insert = Slide::create([
            'name' => $name,
           'link' => $url,
           'thumb' => $nameFile,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return (redirect()->back()->with('success', 'Đã thêm slide ' . $name));
    }
    public function getSlidesAll(){
        return (Slide::all());
    }
    public function where($id){
        return (Slide::where('id', $id)->firstOrFail());
    }
    public function update($request, $id){
        $item = Slide::where('id', $id)->firstOrFail();
        if ($request->hasFile('thumb')) {
            $url = 'storage/products/' . $item->thumb;
            unlink($url);
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/products/', $nameFile);
            $item->thumb = $nameFile;
        }
        $name = $request->input('name');
        $link = $request->input('url');
        $item->name = $name;
        $item->link = $link;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/slide/edit/'. $id)->with('success', 'Cập nhật slide "'.$name.'" Thành công!'));
    }
    public function delete($request){
        $id = $request->input('id');
        $item = Slide::where('id', $id)->firstOrFail();
        $url = 'storage/products/' . $item->thumb;
        unlink($url);
        return( $item->delete()) ;
    }
}
