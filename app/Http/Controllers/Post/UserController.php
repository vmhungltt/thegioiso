<?php

namespace App\Http\Controllers\Post;

use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Http\Services\ShipService\ShipService;
use App\Http\Services\UserService\UserService;
use Exception;
use App\Models\User;

class UserController extends Controller
{
    protected $addressService;
    protected $userService;
    public function __construct()
    {
        $this->addressService = new ShipService();
        $this->userService = new UserService();
    }

    public function handleCallbackGoogle()
    {
        try {

            $user = Socialite::driver('google')->user();
            if (Auth::attempt(['google_id' => $user->id, 'login_type' => 3, 'password' => 'login_socialte'])) {
                return (redirect('/index.html'));
            } else {
                $insert = new User();
                $insert->name = $user->name;
                $insert->email = $user->email;
                $insert->google_id = $user->id;
                $insert->login_type = 3;
                $insert->password = bcrypt('login_socialte');
                $insert->created_at = date('Y-m-d H:i:s');
                $insert->updated_at = date('Y-m-d H:i:s');
                $insert->save();
                if (Auth::attempt(['google_id' => $user->id, 'login_type' => 3, 'password' => 'login_socialte'])) {
                    return (redirect('/index.html'));
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function handleCallbackFaceBook(Request $request)
    {

        try {
            $user = Socialite::driver('facebook')->user();

            if (Auth::attempt(['facebook_id' => $user->id, 'login_type' => 2, 'password' => 'login_socialte'])) {
                return (redirect('/index.html'));
            } else {
                $insert = new User();
                $insert->name = $user->name;
                $insert->email = $user->email;
                $insert->facebook_id = $user->id;
                $insert->login_type = 2;
                $insert->password = bcrypt('login_socialte');
                $insert->created_at = date('Y-m-d H:i:s');
                $insert->updated_at = date('Y-m-d H:i:s');
                $insert->save();
                if (Auth::attempt(['facebook_id' => $user->id, 'login_type' => 2, 'password' => 'login_socialte'])) {
                    return (redirect('/index.html'));
                }
            }
        } catch (Exception  $e) {

            dd($e->getMessage());
        }
    }

    public function redirectToFB()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $email = $request->input('emailAjax');
        $password = $request->input('passWordAjax');
        if (Auth::attempt(['email' => $email, 'password' => $password, 'login_type' => 1])) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'stastus' => 'error',
            ]);
        }
    }
    public function showFormLogin()
    {
        return (view('post.users.login'));
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
    public function getWards(Request $request)
    {
        return ($this->addressService->getWardsUser($request));
    }
    public function getDistrict(Request $request)
    {
        return ($this->addressService->getDistrictUser($request));
    }
    public function showFormConfig()
    {
        if (Auth::user()->phone_number != '') {
            dd('đã thiết lập');
        }
        $listCity = City::all();
        return (view('post.users.configInfor', compact('listCity')));
    }
    public function setInfor(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'city' => 'required',

            'number' => 'required',
            'address-detail' => 'required'
        ], [
            'city.required' => '* Vui lòng lựa chọn khu vực',
            'number.required' => '* Số điện thoại không được để trống',
            'address-detail.required' => '* Địa chỉ không được để trống',
        ]);
        //dd($request->all());
        return ($this->userService->setInfor($request));
    }
}
