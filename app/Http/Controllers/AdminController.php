<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return (view('admin.users.login'));
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
        // echo $request->input('email');
        $request->validate(
            [
                'password' => 'required|min:6|max:19',
                'email' => 'required',
            ],
            [
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải từ 6 đến 19 ký tự',
                'password.max' => 'Mật khẩu phải từ 6 đến 19 ký tự',
                'email.required' => 'Email không được để trống',
            ]
        );
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember');
        if($remember == null){
            $remember = false;
            if($request->hasCookie('email')){
                Cookie::queue(Cookie::forget('email'));
                Cookie::queue(Cookie::forget('password'));
           }
        } else {
            $remember = true;
            Cookie::queue('email', $email, 1700);
            Cookie::queue('password', $password, 1700);
        }
       // dd($remember);

        if (Auth::attempt(['email' => $email, 'password' => $password, 'login_type' => 1], $remember)) {
           return (redirect('admin/'));
        } else {
           return( redirect()->back()->with('error', 'Tên tài khoản hoặc mật khẩu không chính xác!'));
        }
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
