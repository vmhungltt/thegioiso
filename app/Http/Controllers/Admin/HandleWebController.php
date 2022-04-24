<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\OderService\OderService;
use App\Http\Services\UserService\UserService;
use App\Http\Services\CommentService\CommentService;
class HandleWebController extends Controller
{
    protected $oderService, $userService, $commentService;
    public function __construct(){
        $this->oderService = new OderService();
        $this->userService = new UserService();
        $this->commentService = new CommentService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //  $dataOder = $this->oderService->getAllOder()->take(6);
       $dataComment = $this->commentService->getAllComment();

       $dataUser = $this->userService->getAllUser();
       $dataOder = $this->oderService->getAllOder();
        if (Auth::check()) {
            $title = 'DashBoard';
            $user = Auth::user();
           return (view('admin.home', compact('title','user', 'dataOder', 'dataUser', 'dataComment')));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
