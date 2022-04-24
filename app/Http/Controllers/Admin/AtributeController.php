<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\AtributeService\AtributeService;

class AtributeController extends Controller
{
    protected $serviceAtribute ;
    public function __construct()
    {
        $this->serviceAtribute = new AtributeService();
    }
    public function showListFlatForm(){
        $user = Auth::user();
        $listPlatForm = $this->serviceAtribute->getPlatFormAll();

        $title = 'Danh sách nền tảng thuốc tính';
       return (view('admin.atribute.listPlatForm', compact('listPlatForm','user', 'title')));
    }
    public function showDetailPlatForm($slug){
          $dataItem = $this->serviceAtribute->getBusinessPlatformSlug($slug);
          //dd($dataItem->getProductType);
          $user = Auth::user();
          $title = 'Danh sách thuộc tính nền tảng "'.$dataItem->name.'"';
          return (view('admin.atribute.detail', compact('dataItem','user', 'title')));
    }
    public function addTypePlatForm(Request $request){
        $validated = $request->validate([
            'type' => 'required',

        ], [
            'type.required' => 'Lựa chọn ít nhất một thuộc tính',
        ]);
        if($request->platform == 0){
            return  redirect()->back()->with(['msg' => 'Bạn chưa lựa chọn nền tảng nào']);
        }
     //   dd($request->all());
      return ($this->serviceAtribute->insertDetail($request));
    }
    public function showForm(){
        $user = Auth::user();
        $listPlatForm = $this->serviceAtribute->getPlatFormAll();
        $listType = $this->serviceAtribute->getAtributeAll();
        $title = 'Thêm thuộc tính sản phẩm cho nền tảng';
        return (view('admin.atribute.addTypePlatform', compact('listType','listPlatForm','user', 'title')));
    }
    public function getProductType(Request $request){
        return ($this->serviceAtribute->getProductType($request));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dataPlatForm = $this->serviceAtribute->getPlatFormAll();
        $dataItem = $this->serviceAtribute->getAtributeAll();
        $user = Auth::user();
        $title = 'Danh sách thuộc tính sản phẩm';
        return (view('admin.atribute.list', compact('dataPlatForm','dataItem', 'user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();
        $title = 'Thêm thuộc tính sản phẩm mới';
        return (view('admin.atribute.add', compact( 'user', 'title')));
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
            'name' => 'required',
            'description' => 'required',
        ], [
            'description.required' => 'Mô tả không được để trống',
            'name.required' => 'Tên danh mục không được để trống',
        ]);
  return ($this->serviceAtribute->insert($request));
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

        $dataItem = $this->serviceAtribute->where($id);
        $user = Auth::user();
        $title = 'Sửa nền tảng ' .$dataItem->description;

        return (view('admin.atribute.edit', compact('user', 'title', 'dataItem')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function destroyDetail(Request $request){
      return ($this->serviceAtribute->destroyDetail($request));
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
        //echo 'update';
        //
        return ($this->serviceAtribute->update($request, $id));
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
        return $this->serviceAtribute->delete($request);
    }
}
