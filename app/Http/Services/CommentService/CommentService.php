<?php

namespace App\Http\Services\CommentService;

use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class CommentService
{
    public static function createMenuTree($menuList, $parent_id, $lever)
    {
        $menuTree = array();
        foreach ($menuList as $key => $menu) {
            if ($menu->parent_id == $parent_id) {
                $menu['lever'] = $lever;
                $menuTree[] = $menu;
                unset($menuList[$key]);
                $children = self::createMenuTree($menuList, $menu->id, $lever + 1);
                $menuTree = array_merge($menuTree, $children);
            }
        }
        return ($menuTree);
    }
   public function getComment($slug){
      $dataItem = Product::where('slug', $slug)->firstOrFail();

      return($dataItem);
   }
   public function delete($request){
       $id = $request->input('id');
       $data = Comment::where('id',$id)->orWhere('parent_id', $id)->delete();
       return ($data);
   }
   public function showDashBoard($request){
       $dataItem = Comment::find($request->input('id'));
      return (view('admin.comment.outputDashBoard', compact('dataItem')));
   }
   public function getRating(){
    //$data = Product::find(11)->getComment()->where('vote','<>', 0)->get();
   //dd($data);
   $listProduct = Product::all();
     $user = Auth::user();
     $title = 'Danh sách xếp hạng sản phẩm';
       return(view('admin.comment.listRating', compact('listProduct', 'user', 'title')));

   }
   public function showRating($slug){
       $voteRating = array();
       $dataItem = Product::where('slug', $slug)->firstOrFail();
       $user = Auth::user();
       $title = 'Danh sách xếp hạng sản phẩm "'.$dataItem->name.'"';
       $error = true;
       $sumVote = 0;
       $sumTotal = 0;
       for($i = 1; $i <= 5; $i ++){
        $voteRating[$i] = count( $dataItem->getComment()->where('vote', $i)->where('vote', '!=', 0)->get());
        $sumVote  = $sumVote +($i *  count( $dataItem->getComment()->where('vote', $i)->where('vote', '!=', 0)->get()));
        $sumTotal = $sumTotal + count( $dataItem->getComment()->where('vote', $i)->where('vote', '<>', 0)->get());
       }
       if($sumVote == 0 || $sumTotal == 0){
        return(view('admin.comment.detailRating',  compact( 'error', 'user', 'title')));
       }
      // $vote5 = (100/$sumTotal) * $voteRating[5];
       //echo  $sumVote/$sumTotal;
      // $data = $dataItem->getComment()->where('vote', '<>', 0)->get();
     // $data = $dataItem->getComment()->where('vote', 5)->where('vote', '<>', 0)->get();
      // dd($data);


   return(view('admin.comment.detailRating',  compact( 'voteRating', 'dataItem', 'sumVote','sumTotal', 'user', 'title')));
   }
   public function showRatingMore($request){
      $vote =  $request->input('id');
      $id_product = $request->input('slug');

      $dataItem = Comment::where('vote', $vote)->where('product_id', $id_product)->get();
      return (view('admin.comment.outputRating', compact('dataItem')));
   }
   public function getAllComment(){
       return (Comment::orderBy('created_at', 'DESC')->where('parent_id', 0)->where('active', 0)->paginate(5));
   }
}
