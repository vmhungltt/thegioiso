<?php

namespace App\Http\Services\BrandsService;

use Illuminate\Support\Str;
use App\Models\Brands;
use App\Helper\Helper;

class BrandsService
{
    public function insert($request)
    {
        $name = $request->input('name');
        $active = $request->input('active');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $insert = Brands::create([
            'name' => $name,
            'active' => $active,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        return (redirect()->back()->with('success', 'Đã thêm danh mục ' . $name));
    }
    public function getBrandsAll(){
        return (Brands::all());
    }
    public function where($id){
        return (Brands::where('id', $id)->firstOrFail());
    }
    public function update($request, $id){
        $item = Brands::where('id', $id)->firstOrFail();
        $name = $request->input('name');
        $active = $request->input('active');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $item->name = $name;
        $item->active = $active;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/brands/edit/'. $id)->with('success', 'Cập nhật thương hiệu "'.$name.'" Thành công!'));
    }
    public function delete($request){
        $id = $request->input('id');
        return( Brands::where('id',$id)->delete()) ;
    }
}
