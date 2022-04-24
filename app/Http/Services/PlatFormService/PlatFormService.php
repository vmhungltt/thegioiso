<?php

namespace App\Http\Services\PlatFormService;

use Illuminate\Support\Str;
use App\Models\BusinessPlatform;

class PlatFormService
{
    public function insert($request){
        $name = $request->input('name');
        $active = $request->input('active');
        $slug = Str::slug($name, '-');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $insert = BusinessPlatform::create([
            'name' => $name,
            'slug' => $slug,
            'active' => $active,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return (redirect()->back()->with('success', 'Đã thêm nền tảng ' . $name));
    }
    public function getPlatFormAll(){
        return(BusinessPlatform::all());
    }
    public function where($slug){
        $result  = BusinessPlatform::where('slug', '=', $slug)->firstOrFail();
        return ($result);
    }
    public function update($request, $slug){
        $item = BusinessPlatform::where('slug', $slug)->firstOrFail();
        $name = $request->input('name');
        $active = $request->input('active');
        $slug = Str::slug($name, '-');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $item->name = $name;
        $item->active = $active;
        $item->slug = $slug;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/platform/edit/'. $slug)->with('success', 'Cập nhật nền tảng "'.$name.'" Thành công!'));
    }
    public function delete($request){
        $id = $request->input('id');
        return( BusinessPlatform::where('id',$id)->delete()) ;
    }
}
