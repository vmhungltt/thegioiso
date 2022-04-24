<?php

namespace App\Http\Services\ProductService;

use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\ProductValue;
use App\Models\Values;
use Illuminate\Support\Facades\Auth;


class ProductService
{
    public function removeViewModel($request){
        foreach ($request->input('array_product') as $value){
            $updata = Product::where('id', $value)->first();
            $updata->view_model = 0 ;
            $updata->save();
        }
    }
    public function changeViewModel($request){
         $viewType = $request->input('view_type');
         foreach ($request->input('array_product') as $value){
             $updata = Product::where('id', $value)->first();
             $updata->view_model = $viewType;
             $updata->save();
         }
    }
    public function getProductAjax($request){
              //  echo $request->input('category_id');
       // echo $request->input('view_type');
        $dataItem = Product::where('category_id', $request->input('category_id'))->get();
        $viewType = $request->input('view_type');
        return (view('admin.product.outputProduct', compact('dataItem', 'viewType')));
    }
    public function getProductListAjax($request){
        $viewType = $request->input('view_type');
  $dataItem = Product::where('category_id', $request->input('category_id'))->where('view_model', $viewType)->get();

  return (view('admin.product.outputProductList', compact('dataItem', 'viewType')));
}
    public function getCategoryAjax($request){
        $platFormId = $request->input('platForm_id');
        $dataItem = Category::where('business_platform_id',  $platFormId)->get();
        return (view('admin.product.outputCategory', compact('dataItem')));
    }
    public function insertProductValues($request, $slug)
    {
        $product_id = Product::where('slug', $slug)->firstOrFail();
        if(count(ProductValue::where('product_id', $product_id->id)->get()) > 0 ){
               dd('cập nhật');
        }else {
            foreach ($request->value as $value) {
                $insert = new ProductValue();
                $data = Values::find($value);
                $type_id = $data->type_id;
                $insert->product_id = $product_id->id;
                $insert->type_id =  $type_id;
                $insert->value_id =  $value;
                $insert->created_at = date('Y-m-d H:i:s');
                $insert->updated_at = date('Y-m-d H:i:s');
                $insert->save();
            }
            dd('đã thêm');
        }

       // return (redirect()->back()->with('success', 'Đã thiết lập cấu hình cho sản phẩm ' .$product_id->name));
    }
    public function whereProductType($platForm_id)
    {
        return (ProductType::where('platform_id', $platForm_id)->get());
    }
    public function CheckFile($file)
    {

        $Error = array();
        $validTypes = array('webp', 'jpg', 'jpeg', 'png', 'bmp');
        foreach ($file as $key => $value) {
            $nameFile = $file[$key]->getClientOriginalName(); // Tên file
            $nameType = $file[$key]->extension(); // Đuôi file
            $Filebytes = $file[$key]->getSize(); // Kích thước File
            if (!in_array($nameType, $validTypes)) {
                $Error[] = "* File '$nameFile' không đúng với định dạng hình ảnh";
            }
            if ($Filebytes > (8 * 1024 * 1024)) {
                $Error[] = "* File '$nameFile' vượt quá dung lượng cho phép";
            }
        }
        return ($Error);
    }
    public function insert($request)
    {
        $idPost = Auth::id();
        $name = $request->input('name');
        $category = $request->input('category');
        $description = $request->input('description');
        $content = $request->input('content');
        $active = $request->input('active');
        $slug = Str::slug($name, '-');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $price = $request->input('price');
        $priceSale = $request->input('price_sale');
        if ($request->hasFile('thumb')) {
            $file = $request->thumb;
            $typeLastFile =  $request->thumb[0]->extension();
            $nameFile = md5(uniqid()) . '.' . $typeLastFile;
            $upload = $file[0]->storeAs('public/products/', $nameFile);
        }
        $insert = Product::create([
            'view_model' => 0,
            'name' => $name,
            'category_id' => $category,
            'description' => $description,
            'content' => $content,
            'slug' => $slug,
            'price' => $price,
            'price_sale' => $priceSale,
            'active' => $active,
            'thumb' =>  $nameFile,
            'admin_post_id' =>  $idPost,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        return (redirect()->back()->with('success', 'Đã thêm sản phẩm ' . $name));
    }
    public function getAll()
    {
        return (Product::all());
    }
    public function where($slug)
    {
        $result  = Product::where('slug', '=', $slug)->firstOrFail();
        return ($result);
    }
    public function update($request, $slug)
    {

        $item = Product::where('slug', $slug)->firstOrFail();
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
        $category = $request->input('category');
        $description = $request->input('description');
        $content = $request->input('content');
        $active = $request->input('active');
        $slug = Str::slug($name, '-');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $price = $request->input('price');
        $priceSale = $request->input('price_sale');

        /* Save product*/
        $item->name = $name;
        $item->category_id = $category;
        $item->description = $description;
        $item->content = $content;
        $item->active = $active;
        $item->slug = $slug;
        $item->price = $price;
        $item->price_sale = $priceSale;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        /* End save product */

        return (redirect('/admin/product/edit/' . $slug)->with('success', 'Cập nhật danh mục "' . $name . '" Thành công!'));
    }
    public function delete($request)
    {
        $id = $request->input('id');
        $item = Product::where('id', $id)->firstOrFail();
        $url = 'storage/products/' . $item->thumb;
        unlink($url);
        return ($item->delete());
    }
}
