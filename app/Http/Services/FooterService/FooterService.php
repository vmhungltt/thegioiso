<?php

namespace App\Http\Services\FooterService;

use Illuminate\Support\Str;

use App\Models\Footer;

class FooterService
{
    public function insert($request)
    {
        $insert = new Footer();
        $insert->name = $request->input('name');
        $insert->content = $request->input('content');
        $insert->slug =  Str::slug($request->input('name'), '-');
        $insert->created_at = date('Y-m-d H:i:s');
        $insert->updated_at = date('Y-m-d H:i:s');
        $insert->save();
        return (redirect()->back()->with('success', 'Đã thêm footer ' .$request->input('name')));
    }
    public function getList(){
        return (Footer::all());
    }
    public function destroy($request){
        return (Footer::destroy($request->input('id')));
    }
    public function whereSlug($slug){
        return (Footer::where('slug', $slug)->firstOrFail());
    }
    public function update($request, $slug){
        $insert = Footer::where('slug', $slug)->firstOrFail();
        $insert->name = $request->input('name');
        $insert->slug = Str::slug($request->input('name'), '-');
        $insert->content = $request->input('content');
        $insert->updated_at = date('Y-m-d H:i:s');
        $insert->save();
        return (redirect('/admin/config-footer/edit/'. Str::slug($request->input('name'), '-'))->with('success', 'Cập nhật nền tảng "'.$request->input('name').'" Thành công!'));
    }
}
