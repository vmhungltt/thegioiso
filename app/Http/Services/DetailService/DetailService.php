<?php

namespace App\Http\Services\DetailService;

use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\BusinessPlatform;
use App\Models\Comment;
use App\Models\News;
class DetailService
{
    public function getNews(){
         return (News::all());
    }
    public function wherePlatForm($slug){
        return (BusinessPlatform::where('slug', $slug)->firstOrFail());
    }
    public function getOrWhere($array){
        return (BusinessPlatform::whereIn('slug',$array)->get());
    }
    public function postComment($request, $slugProduct)
    {

        $product_id = Product::where('slug', $slugProduct)->first()->id;
        $name = $request->input('name');
        $phoneNumber = $request->input('phone_number');
        $content = $request->input('content');
        $email = $request->input('email');
        $rating = $request->input('rating');
        $parent_id = $request->input('parent_id');
        $insertComment = new Comment();
        $insertComment->product_id = $product_id;
        $insertComment->parent_id = $parent_id;
        $insertComment->vote = $rating;
        $insertComment->content = $content;
        $insertComment->name = $name;
        $insertComment->number_phone = $phoneNumber;
        $insertComment->email = $email;
        $insertComment->active = 0;
        $insertComment->thumb = 'NaN';
        $insertComment->created_at = date('Y-m-d H:i:s');
        $insertComment->updated_at = date('Y-m-d H:i:s');
        $insertComment->save();
        $pageCurrent = $request->input('page_current');
        $pageLimit = 8;
        $offsetCurrent = ($pageCurrent - 1) * $pageLimit;

        $dataProduct = Product::where('slug', $slugProduct)->first();
        $sumPage = $dataProduct->getComment()
            ->where('parent_id', 0)
            ->get();
        $dataComment = $dataProduct->getComment()
            ->where('parent_id', 0)
            ->orderBy('id', 'desc')
            ->offset($offsetCurrent)
            ->limit($pageLimit)
            ->get();
        return (view('post.comment.outputComment', compact('pageLimit', 'sumPage', 'pageCurrent', 'dataComment')));
    }
    public function whereProduct($slug)
    {
        return (Product::where('slug', $slug)->firstOrfail());
    }
    public function showPagination($request, $slugProduct)
    {
        //   echo 'page : "'.$request->input('page').'" <br/>';

        $pageCurrent = $request->input('page');
        $pageLimit = 8;
        $offsetCurrent = ($pageCurrent - 1) * $pageLimit;

        $dataProduct = Product::where('slug', $slugProduct)->first();
        $sumPage = $dataProduct->getComment()
            ->where('parent_id', 0)
            ->get();
        $dataComment = $dataProduct->getComment()
            ->where('parent_id', 0)
            ->orderBy('id', 'desc')
            ->offset($offsetCurrent)
            ->limit($pageLimit)
            ->get();
        /*foreach ($dataComment as $value) {
            echo $value->name;
        }*/
        return (view('post.comment.outputComment', compact('pageLimit', 'sumPage', 'pageCurrent', 'dataComment')));
    }
}
