<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DetailService\DetailService;
use App\Http\Services\Color\ColorService;
use App\Http\Services\ShipService\ShipService;
class DetailController extends Controller
{
    protected $detailService, $colorService, $addressService;
    public function __construct(){
        $this->detailService = new DetailService();
        $this->colorService = new ColorService();
        $this->addressService = new ShipService();
    }
    public function getDetailColor(Request $request){
         return ($this->colorService->getDetailColor($request));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDistrict(Request $request){
      return ($this->addressService->getDistrictPost($request));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWards(Request $request){
        return ($this->addressService->getWardPost($request));
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
    public function show($platForm, $slugProduct)
    {
        $detailProduct = $this->detailService->whereProduct($slugProduct);
       $array = ['phu-kien', $platForm];

      $suggest  = $this->detailService->getOrWhere($array);

     $newsSuggest = $this->detailService->getNews();
     //dd($newsSuggest);
       return (view('post.detail', compact('suggest','detailProduct', 'newsSuggest')));
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
    public function handleComment(Request $request, $platForm, $slugProduct){
          return ($this->detailService->postComment($request,  $slugProduct));
    }
    public function showPagination(Request $request,$platForm, $slugProduct){
       // echo  $slugProduct;
      return ($this->detailService->showPagination($request, $slugProduct));
    }
}
