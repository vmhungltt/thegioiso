<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ProductService\ProductService;
use App\Http\Services\PlatFormService\PlatFormService;
class ViewModeController extends Controller
{
    protected $serviceProduct;
    protected $servicePlatForm;
    public function __construct()
    {
$this->servicePlatForm = new PlatFormService();
        $this->serviceProduct = new ProductService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeViewProduct(Request $request){
        return ($this->serviceProduct->removeViewModel($request));
    }
    public function index()
    {
        //
        $listPlatForm = $this->servicePlatForm->getPlatFormAll();
        $user = Auth::user();
        $title = 'Danh sách kiểu hiển thị sản phẩm';
        return (view('admin.viewMode.list', compact('listPlatForm', 'user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listPlatForm = $this->servicePlatForm->getPlatFormAll();
        $user = Auth::user();
        $title = 'Thêm kiểu hiện thị mới';
        return (view('admin.viewMode.add', compact('listPlatForm', 'user', 'title')));
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategory(Request $request)
    {
        return ($this->serviceProduct->getCategoryAjax($request));
    }
    public function getProduct(Request $request){

       return ($this->serviceProduct->getProductAjax($request));
    }
    public function getProductList(Request $request){
        return ($this->serviceProduct->getProductListAjax($request));
     }
    public function changeViewModel(Request $request){
        return ($this->serviceProduct->changeViewModel($request));
    }
}
