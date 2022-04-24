<?php

namespace App\Http\Services\Color;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ManageColor;
use App\Models\LibraryColor;

class ColorService
{
    public function getDetailColor($request){
        $color_id = $request->input('color_id');
        $dataItem = ManageColor::where('id', $color_id)->first();
      return (view('post.color.outputDetailColor', compact('dataItem')));
    }
    public function insertColor($request)
    {
        if ($request->hasFile('thumb')) {
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/library/', $nameFile);
        }
        $nameColor = $request->input('name');
        $product_id = $request->input('product_id');
        $price = $request->input('price');
        $priceSale = $request->input('price_sale');
        $active = $request->input('active');
        $quantity = $request->input('quantity');
        $idManageColor = DB::table('manage_color')->insertGetId(
            [
                'name_color' => $nameColor,
                'product_id' => $product_id,
                'price' => $price,
                'price_sale' => $priceSale,
                'active' => $active,
                'thumb' =>  $nameFile,
                'quantity' => $quantity,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        if ($request->hasFile('library')) {
            $arrayFile = $request->library;
            foreach ($arrayFile as $file) {
                $typeLastFile =  $file->extension();
                $nameFile = md5(uniqid()) . '.' . $typeLastFile;
                $upload = $file->storeAs('public/library/', $nameFile);
                $insertLibrary = LibraryColor::create([
                    'manage_color_id' => $idManageColor,
                    'thumb' =>  $nameFile,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        return (redirect()->back()->with('success', 'Đã thêm "' . $nameColor . ' " cho sản phẩm '));
    }
    public function update($request, $id)
    {
        $item = ManageColor::where('id', $id)->firstOrFail();
        if ($request->hasFile('thumb')) {
            $url = 'storage/library/' . $item->thumb;
            unlink($url);
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/library/', $nameFile);
            $item->thumb = $nameFile;
        }
        $item->product_id = $request->input('product_id');
        $item->name_color = $request->input('name');
        $item->price = $request->input('price');
        $item->price_sale = $request->input('price_sale');
        $item->active = $request->input('active');
        $item->quantity = $request->input('quantity');
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/color/edit/' . $id)->with('success', 'Cập nhật danh mục "' . $request->input('name') . '" Thành công!'));
    }
    public function deleteLibrary($request)
    {
        $id = $request->input('id');
        $data = LibraryColor::find($id);
        $url = 'storage/library/' . $data->thumb;
        unlink($url);
        $data->delete();
        return ($data);
    }
    public function uploadLibrary($request, $id)
    {
        if ($request->hasFile('library')) {
            $arrayFile = $request->library;
            foreach ($arrayFile as $file) {
                $typeLastFile =  $file->extension();
                $nameFile = md5(uniqid()) . '.' . $typeLastFile;
                $upload = $file->storeAs('public/library/', $nameFile);
                $insertLibrary = LibraryColor::create([
                    'manage_color_id' => $id,
                    'thumb' =>  $nameFile,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            return (redirect('/admin/color/library/' . $id)->with('success', 'Cập nhật thư viện ảnh Thành công!'));
        }
    }
}
