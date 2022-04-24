<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Product;
use App\Models\BusinessPlatform;
use App\Models\Footer;
class HomeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginationAjax(Request $request){
        echo 'run';
    }
    public function index()
    {
        $dataProduct = Product::all();
        $dataSlide = Slide::all();
        $dataPlatForm = BusinessPlatform::all();
        return (view('post.index', compact('dataPlatForm', 'dataSlide')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchAjax(Request $request)
    {
        $key = $request->input('keyboard');
        $dataItem = Product::where('name', 'like', '%' . $key . '%')->take(10)->get();
        return (view('post.product.outputProductSearch', compact('dataItem')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resultSearch(Request $request)
    {


        $dataItem = BusinessPlatform::all();

        $arrayResult = [];
        $keyBoard = $request->input('key');
        $total = $activePlatForm = 0;

        foreach ($dataItem as $plat) {
            $sum = 0;
            foreach ($plat->getCategory as $pr) {
                $sum = $sum + count($pr->getProduct()->where('name', 'like', '%' . $keyBoard . '%')->get());
            }
            $arrayResult[] = [
                'name' => $plat->name,
                'quantity' => $sum,
            ];
            $total = $total + $sum;
        }
        foreach ($arrayResult as $key => $value) {
            if ($value['quantity'] != 0) {
                $activePlatForm = $key;
                break;
            }
        }

        return (view('post.resultSearch', compact('keyBoard', 'activePlatForm', 'total', 'dataItem', 'arrayResult')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resultPlatForm(Request $request)
    {

        $keyBoard = $request->input('keyboard');
        $page = $request->input('page');
        $dataItem = BusinessPlatform::where('name', $request->input('name'))->first();
        $total = 0;

        foreach ($dataItem->getCategory as $ct) {
         $total = $total + count($ct->getProduct()->where('name', 'LIKE', "%{$request->input('keyboard')}%")->get()) ;
        }

        return (view('post.product.outputProductPlatFormSeacher', compact('page','keyBoard', 'dataItem', 'total')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showInforFooter($slug){
       $dataItem = Footer::where('slug', $slug)->firstOrFail();
         return (view('post.infor', compact('dataItem')));
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
