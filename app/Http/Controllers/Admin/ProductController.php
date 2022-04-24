<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\CategoryService\CategoryService;
use App\Http\Services\ProductService\ProductService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ProductController extends Controller
{
    protected $categoryService;
    protected $productService;
    public function __construct()
    {
        $this->categoryService =  new CategoryService();
        $this->productService = new ProductService();
    }
    public function storeConfig(Request $request, $slug){
        return ($this->productService->insertProductValues($request, $slug));
    }
    public function showConfig($slug){
        $dataItem = $this->productService->where($slug);
        $platFormId = $dataItem->getCategory->getBusiness->id;
        $listProductType = $this->productService->whereProductType($platFormId);
      //  dd($listProductType);
      $user = Auth::user();
      $title = 'Thiết lập cấu hình cho sản phẩm "'.$dataItem->name.'"';
      return (view('admin.product.config', compact('dataItem','listProductType','user', 'title')));

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $listProduct = $this->productService->getAll();
        $user = Auth::user();
        $title = 'Danh sách sản phẩm';
        return (view('admin.product.list', compact('listProduct','user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $listCategory =  $this->categoryService->getCategoryAll();
        $user = Auth::user();
        $title = 'Thêm sản phẩm mới';
        return (view('admin.product.add', compact('listCategory', 'user', 'title')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate([
            'name' => 'required|unique:product,name',
            'description' => 'required',
            'price' => 'required',
            'price_sale' => 'required',
            'content' => 'required',
            'thumb' => 'required'
        ], [
            'price.required' => 'Giá gốc không được để trống',
            'price_sale.required' => 'Giá ưu đãi không được để trống',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'name.required' => 'Tên sản phẩm không được để trống',
            'description.required' => 'Mô tả không được để trống',
            'thumb.required' => 'Vui lòng chọn ít nhất một ảnh',
            'content.required' => 'Nội dung không được để trống',
        ]);
        $file = $request->thumb;
        $errorFile = $this->productService->CheckFile($file);
        if (count($errorFile)  != 0) {
            return redirect()->back()->withErrors(['thumb' => $errorFile[0]]);
        }
        return ($this->productService->insert($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $item = $this->productService->where($slug);
     //   dd($item);
        //
      //  echo 'chỉnh sửa sản phẩm với đường dẫn thân thiện '. $slug;
      $listCategory =  $this->categoryService->getCategoryAll();
      $user = Auth::user();
      $title = 'Sửa sản phẩm '. $item->name;
      return (view('admin.product.edit', compact('item','listCategory', 'user', 'title')));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'name' => 'required|',
            'description' => 'required',
            'price' => 'required',
            'price_sale' => 'required',
            'content' => 'required',
        ], [
            'price.required' => 'Giá gốc không được để trống',
            'price_sale.required' => 'Giá ưu đãi không được để trống',
            'name.required' => 'Tên sản phẩm không được để trống',
            'description.required' => 'Mô tả không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);
        if($request->hasFile('thumb')){
            $file = $request->thumb;
            $errorFile = $this->productService->CheckFile($file);
            if (count($errorFile)  != 0) {
                return redirect()->back()->withErrors(['thumb' => $errorFile[0]]);
            }
        }

       // echo 'update cho sản phẩm ở đây';
        return ($this->productService->update($request, $slug));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $result = $this->productService->delete($request);
        if ($result) {
            return (response()->json([
                'error' => false,
                'messenge' => 'Xóa Thành Công ',
            ]));
        }
    }
}
