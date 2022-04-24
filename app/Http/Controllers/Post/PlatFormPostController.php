<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DetailPlatFormService\DetailPlatFormService;
use App\Models\Product;
use App\Models\BusinessPlatform;

class PlatFormPostController extends Controller
{
    protected $postService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->postService = new DetailPlatFormService();
    }
    public function minMaxPrice($array)
    {
        $tempArray = [];
        foreach ($array as $value) {
            if ($value == 1) {
                array_push($tempArray, 0, 2000000);
            }
            if ($value == 2) {
                array_push($tempArray, 2000000, 4000000);
            }
            if ($value == 3) {
                array_push($tempArray, 4000000, 7000000);
            }
            if ($value == 4) {
                array_push($tempArray, 7000000, 13000000);
            }
            if ($value == 5) {
                array_push($tempArray, 13000000, 20000000);
            }
            if ($value == 6) {
                array_push($tempArray, 20000000, 200000000);
            }
        }
        return ([min($tempArray), max($tempArray)]);
    }

    public function index(Request $request,$platform)
    {


        $page = 0;

        $dataFilter = $this->postService->wherePlatForm($platform)->getProduct();
        if ($request->has('ca')) {
            $arrayCategory = explode(',', $request->ca);
            $dataFilter = $dataFilter->whereIn('category_id',  $arrayCategory);
        }
        if ($request->has('pr')) {
            $arrayPrice  =  $this->minMaxPrice(explode(',', $request->pr));
            $dataFilter = $dataFilter->whereBetween('price_sale', $arrayPrice);
        }
        if ($request->has('ra') || $request->has('ro')) {
            $arrayVariant = [];
            $arrayQuery = [];
            if ($request->has('ra')) {
                $arrayVariant = ['type_id' => 2];
                $arrayRam = explode(',', $request->ra);
                foreach ($dataFilter->get() as $value) {
                    foreach ($value->getProductValue()->where($arrayVariant)->whereIn('value_id', $arrayRam)->get() as $valueFilter) {
                        if (!in_array($valueFilter->getProduct->id, $arrayQuery)) {
                            array_push($arrayQuery, (int) $valueFilter->getProduct->id);
                        }
                    }
                }
            }
            if ($request->has('ro')) {
                $arrayVariant = ['type_id' => 3];
                $arrayRom = explode(',', $request->ro);
                foreach ($dataFilter->get() as $value) {
                    foreach ($value->getProductValue()->where($arrayVariant)->whereIn('value_id', $arrayRom)->get() as $valueFilter) {
                        if (!in_array($valueFilter->getProduct->id, $arrayQuery)) {
                            array_push($arrayQuery, (int) $valueFilter->getProduct->id);
                        }
                    }
                }
            }
            $dataFilter = $this->postService->getProductWhereIn($arrayQuery);
        }
        if ($request->has('sort')) {
            if ($request->sort == 'desc') {
                $dataFilter->orderByDesc('price_sale');
            }
            if ($request->sort == 'asc') {
                $dataFilter->orderBy('price_sale', 'asc');
            }
        }
        if($request->has('page')){
            $page = $request->page;
        }
        $sumTotal = count($dataFilter->get());
        $dataFilter = $dataFilter->orderBy('id', 'desc')->limit(($page + 1)*6)->offset(0);






        $dataPlatForm = $this->postService->firstWherePlatForm($platform);

        return (view('post.platForm', compact('page','sumTotal','dataPlatForm', 'dataFilter')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxFilter(Request $request, $platform)
    {

        $dataFilter = $this->postService->wherePlatForm($platform)->getProduct();
        if ($request->has('ca')) {
            $arrayCategory = explode(',', $request->ca);
            $dataFilter = $dataFilter->whereIn('category_id',  $arrayCategory);
        }
        if ($request->has('pr')) {
            $arrayPrice  =  $this->minMaxPrice(explode(',', $request->pr));
            $dataFilter = $dataFilter->whereBetween('price_sale', $arrayPrice);
        }
        if ($request->has('ra') || $request->has('ro')) {
            $arrayVariant = [];
            $arrayQuery = [];
            if ($request->has('ra')) {
                $arrayVariant = ['type_id' => 2];
                $arrayRam = explode(',', $request->ra);
                foreach ($dataFilter->get() as $value) {
                    foreach ($value->getProductValue()->where($arrayVariant)->whereIn('value_id', $arrayRam)->get() as $valueFilter) {
                        if (!in_array($valueFilter->getProduct->id, $arrayQuery)) {
                            array_push($arrayQuery, (int) $valueFilter->getProduct->id);
                        }
                    }
                }
            }
            if ($request->has('ro')) {
                $arrayVariant = ['type_id' => 3];
                $arrayRom = explode(',', $request->ro);
                foreach ($dataFilter->get() as $value) {
                    foreach ($value->getProductValue()->where($arrayVariant)->whereIn('value_id', $arrayRom)->get() as $valueFilter) {
                        if (!in_array($valueFilter->getProduct->id, $arrayQuery)) {
                            array_push($arrayQuery, (int) $valueFilter->getProduct->id);
                        }
                    }
                }
            }
            $dataFilter = $this->postService->getProductWhereIn($arrayQuery);
        }


        return response()->json([
            'quantity' => count($dataFilter->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFilter(Request $request, $platform)
    {
        $page = 0;
        $dataPlatForm = $this->postService->wherePlatForm($platform);
        $dataFilter = $this->postService->wherePlatForm($platform)->getProduct();
        if ($request->has('ca')) {
            $arrayCategory = explode(',', $request->ca);
            $dataFilter = $dataFilter->whereIn('category_id',  $arrayCategory);
        }
        if ($request->has('pr')) {
            $arrayPrice  =  $this->minMaxPrice(explode(',', $request->pr));
            $dataFilter = $dataFilter->whereBetween('price_sale', $arrayPrice);
        }
        if ($request->has('ra') || $request->has('ro')) {
            $arrayVariant = [];
            $arrayQuery = [];
            if ($request->has('ra')) {
                $arrayVariant = ['type_id' => 2];
                $arrayRam = explode(',', $request->ra);
                foreach ($dataFilter->get() as $value) {
                    foreach ($value->getProductValue()->where($arrayVariant)->whereIn('value_id', $arrayRam)->get() as $valueFilter) {
                        if (!in_array($valueFilter->getProduct->id, $arrayQuery)) {
                            array_push($arrayQuery, (int) $valueFilter->getProduct->id);
                        }
                    }
                }
            }
            if ($request->has('ro')) {
                $arrayVariant = ['type_id' => 3];
                $arrayRom = explode(',', $request->ro);
                foreach ($dataFilter->get() as $value) {
                    foreach ($value->getProductValue()->where($arrayVariant)->whereIn('value_id', $arrayRom)->get() as $valueFilter) {
                        if (!in_array($valueFilter->getProduct->id, $arrayQuery)) {
                            array_push($arrayQuery, (int) $valueFilter->getProduct->id);
                        }
                    }
                }
            }
            $dataFilter = $this->postService->getProductWhereIn($arrayQuery);
        }
        if ($request->has('sort')) {
            if ($request->sort == 'desc') {
                $dataFilter->orderByDesc('price_sale');
            }
            if ($request->sort == 'asc') {
                $dataFilter->orderBy('price_sale', 'asc');
            }
        }
        if($request->has('page')){
            $page = $request->page;
        }
        $sumTotal = count($dataFilter->get());
        $dataFilter = $dataFilter->orderBy('id', 'desc')->limit(($page + 1)*6)->offset(0);
        return (view('post.detailPlatForm.detailPlatForm', compact('sumTotal','page','dataFilter', 'dataPlatForm')));
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
    public function destroy($id)
    {
        //
    }
}
