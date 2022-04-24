<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\CommentService\CommentService;
use App\Models\Product;
use App\Models\Comment;
class CommentController extends Controller
{
    protected $commentService;
    public function __construct()
    {
        $this->commentService =  new CommentService();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $listProduct = Product::all();
      //  dd($listProduct[2]->getComment);
       // dd($listProduct[0]->getComment);
        $user = Auth::user();
        $title = 'Danh sách đánh giá sản phẩm';
        return (view('admin.comment.list',compact('listProduct','user', 'title')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRating()
    {
        return ($this->commentService->getRating());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showRating($slug)
    {
        return ($this->commentService->showRating($slug));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $user = Auth::user();

        $result = $this->commentService->getComment($slug);

        $title = 'Danh sách đánh giá sản phẩm "'.$result->name.'"';
       // $dataItem = $this->commentService->createMenuTree($result->getComment, 0, 0);
       $dataItem = Comment::where('product_id', $result->id)->Where('parent_id', 0)->get();

        return (view('admin.comment.detail', compact('dataItem','user', 'title')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showRatingMore(Request $request)
    {
        return ($this->commentService->showRatingMore($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        return($this->commentService->delete($request));
    }
    public function showCommentChild(Request $request){
        $id_parent = $request->input('id');
       $dataItem = Comment::where('parent_id', $id_parent)->get();
      // return Response()->json($dataItem);
     return (view('admin.comment.outputCommentChildren', compact('dataItem')));
    }
    public function showDashboard(Request $request){
        return ($this->commentService->showDashBoard($request));
    }
}
