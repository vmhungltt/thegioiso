<?php

namespace App\Http\Services\AtributeService;

use Illuminate\Support\Str;

use App\Models\Atribute;
use App\Models\BusinessPlatform;
use App\Models\ProductType;

class AtributeService
{
    public function insertDetail($request)
    {
        foreach ($request->input('type') as $value) {
            $insert = new ProductType();
            $insert->platform_id = $request->input('platform');
            $insert->type_id = $value;
            $insert->updated_at = date('Y-m-d H:i:s');
            $insert->created_at =  date('Y-m-d H:i:s');
            $insert->save();
        }
        return (redirect()->back()->with('success', 'Đã thêm các thuộc tính cho nền tảng'));
    }
    public function getProductType($request)
    {
        $type_id = $request->input('type_id');
        $dataItem = ProductType::where('platform_id', $type_id)->get();
        /* foreach($dataItem as $value){
        echo $value->getType->description;
     }*/
        return (view('admin.atribute.outputType', compact('dataItem')));
    }
    public function getBusinessPlatformSlug($slug)
    {
        return (BusinessPlatform::where('slug', $slug)->firstOrFail());
    }
    public function getPlatFormAll()
    {
        return (BusinessPlatform::all());
    }
    public function insert($request)
    {
        $item = new Atribute();
        $item->name = $request->input('name');
        $item->description = $request->input('description');
        $item->updated_at = date('Y-m-d H:i:s');
        $item->created_at =  date('Y-m-d H:i:s');
        $item->save();
        return (redirect()->back()->with('success', 'Đã thêm thuộc tính ' . $request->input('description')));
    }
    public function getAtributeAll()
    {
        return (Atribute::all());
    }
    public function where($id)
    {
        return (Atribute::where('id', $id)->firstOrFail());
    }
    public function update($request, $id)
    {
        $item = Atribute::where('id', $id)->firstOrFail();
        $name = $request->input('name');
        $description = $request->input('description');
        $item->name = $name;
        $item->description =  $description;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/atribute/edit/' . $id)->with('success', 'Cập nhật thương hiệu "' . $description . '" Thành công!'));
    }
    public function delete($request)
    {
        $id = $request->input('id');
        return (Atribute::where('id', $id)->delete());
    }
    public function destroyDetail($request)
    {
        $id = $request->input('id');
        return (ProductType::where('id', $id)->delete());
    }
}
