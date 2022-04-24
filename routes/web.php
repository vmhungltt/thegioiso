<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\HomeController;
use App\Http\Controllers\Post\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\HandleWebController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PlatFormController;
use App\Http\Controllers\Post\PlatFormPostController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\ShipController;
use App\Http\Controllers\Admin\AtributeController;
use App\Http\Controllers\Admin\ValueController;
use App\Http\Controllers\Admin\CategoryNewsController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Post\NewsControllers;
use App\Http\Controllers\Admin\ViewModeController;
use App\Http\Controllers\Post\CartController;
use App\Http\Controllers\Admin\OderController;
use App\Http\Controllers\Post\DetailController;
use App\Http\Controllers\Admin\ConfigController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use App\Models\Footer;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('infor/{slug}', [HomeController::class, 'showInforFooter']);
Route::get('news', [NewsControllers::class, 'index']);
Route::get('news/{slug}', [NewsControllers::class, 'show']);

Route::get('tim-kiem', [HomeController::class, 'resultSearch']);
Route::post('get-keyboard/search-ajax', [HomeController::class, 'searchAjax']);
Route::post('pagination/search-ajax', [HomeController::class, 'paginationAjax']);
Route::post('get-keyboard/platform-ajax', [HomeController::class, 'resultPlatForm']);
Route::get('login-socialite', function(){
    echo '<a href="auth/facebook"> đăng nhập với facebook</a>' . "<br/>";
    echo '<a href="auth/google"> đăng nhập với google</a>';
});
Route::get('callback/facebook', [UserController::class, 'handleCallbackFaceBook']);
Route::get('auth/facebook', [UserController::class, 'redirectToFB']);

Route::get('auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('callback/google', [UserController::class, 'handleCallbackGoogle']);


Route::get('detail/{platform}',[PlatFormPostController::class, 'index'] );
Route::post('get/detail/{platform}',[PlatFormPostController::class, 'getFilter'] );
Route::post('detail/{platform}',[PlatFormPostController::class, 'ajaxFilter'] );
Route::post('add-comment/{platForm}/{slugProduct}', [DetailController::class, 'handleComment']);
Route::post('/{platForm}/{slugProduct}', [DetailController::class, 'showPagination']);
Route::get('login', [UserController::class, 'showFormLogin']);
Route::post('login', [UserController::class, 'index']);
Route::get('/', [HomeController::class, 'index']);
Route::get('/index.html', [HomeController::class, 'index']);
Route::get('/{platForm}/{slugProduct}', [DetailController::class, 'show']);
Route::get('/cart', [CartController::class, 'index']);
/* Router uses for manager post product color*/
Route::post('get-detail-color', [DetailController::class, 'getDetailColor']);
/* End router manager post product color*/
Route::middleware(['authenticationUser'])->group(function () {
    Route::get('set-infor',  [UserController::class, 'showFormConfig']);
    Route::post('set-infor',  [UserController::class, 'setInfor']);
    Route::post('get-district',  [UserController::class, 'getDistrict']);
    Route::post('get-wards',  [UserController::class, 'getWards']);
});

/* Router uses for manager shoping cart*/
Route::post('get-total-cart', [CartController::class, 'store']);
Route::post('get-cart', [CartController::class, 'show']);
Route::post('add-cart', [CartController::class, 'create']);
Route::post('delete-cart', [CartController::class, 'destroy']);
Route::post('delete-cart-detail', [CartController::class, 'deleteCartDetail']);
Route::post('update-cart', [CartController::class, 'update']);
Route::post('handle-otp', [CartController::class, 'handleOTP']);
Route::post('auth-otp', [CartController::class, 'authOTP']);
Route::post('get-transportfee', [CartController::class, 'getTransPortFee']);


/* End router manager address district*/
Route::post('post/address/get-district', [DetailController::class, 'getDistrict']);
Route::post('post/address/get-wards', [DetailController::class, 'getWards']);
/* Router uses for manager address district*/


/* End router  manager address district*/
Route::prefix('/admin')->group(function () {

    Route::middleware(['authenticationAdmin'])->group(function () {


        Route::get('/',[HandleWebController::class, 'index']);
      //  Route::prefix('home', [HandleWebController::class, 'index']);

 /* Router uses for manager config footer */
 Route::prefix('config-footer')->group(function () {
    Route::DELETE('delete',  [ConfigController::class, 'destroy']);
   Route::post('edit/{slug}', [ConfigController::class, 'update'])->name('footer.update.submit');
    Route::get('edit/{slug}', [ConfigController::class, 'show']);
   Route::get('list', [ConfigController::class, 'index']);
    Route::get('add', [ConfigController::class, 'create']);
    Route::post('handlesubmitadd', [ConfigController::class, 'store'])->name('footer.add.submit');
});
/* End router manager config footer*/




        /* Router uses for manager category */
        Route::prefix('category')->group(function () {
            Route::DELETE('delete',  [CategoryController::class, 'destroy']);
            Route::post('edit/{slug}', [CategoryController::class, 'update'])->name('category.update.submit');
            Route::get('edit/{slug}', [CategoryController::class, 'show']);
            Route::get('list', [CategoryController::class, 'index']);
            Route::get('add', [CategoryController::class, 'create']);
            Route::post('handlesubmitadd', [CategoryController::class, 'store'])->name('category.add.submit');
        });
        /* End router manager category*/

        /* Router uses for manager category for news*/
        Route::prefix('category-news')->group(function () {
            Route::DELETE('delete',  [CategoryNewsController::class, 'destroy']);
            Route::post('edit/{slug}', [CategoryNewsController::class, 'update'])->name('categoryNews.update.submit');
            Route::get('edit/{slug}', [CategoryNewsController::class, 'show']);
            Route::get('list', [CategoryNewsController::class, 'index']);
            Route::get('add', [CategoryNewsController::class, 'create']);
            Route::post('handlesubmitadd', [CategoryNewsController::class, 'store'])->name('categoryNews.add.submit');
        });
        /* End router manager category for news*/
        /* Router uses for manager platform */
        Route::prefix('news')->group(function () {
            Route::DELETE('delete',  [NewsController::class, 'destroy']);
            Route::post('edit/{slug}', [NewsController::class, 'update'])->name('news.update.submit');
            Route::get('edit/{slug}', [NewsController::class, 'show']);
            Route::get('list', [NewsController::class, 'index']);
            Route::get('add', [NewsController::class, 'create']);
            Route::post('handlesubmitadd', [NewsController::class, 'store'])->name('news.add.submit');
        });
        /* End router manager platform*/

        /* Router uses for manager product */
        Route::prefix('product')->group(function () {
            Route::get('config/{slug}',  [ProductController::class, 'showConfig']);
            Route::DELETE('delete',  [ProductController::class, 'destroy']);
            Route::post('edit/{slug}', [ProductController::class, 'update'])->name('product.update.submit');
            Route::get('edit/{slug}', [ProductController::class, 'show']);
            Route::get('list', [ProductController::class, 'index']);
            Route::get('add', [ProductController::class, 'create']);
            Route::post('handlesubmitadd', [ProductController::class, 'store'])->name('product.add.submit');
            Route::post('handle-config/{slug}', [ProductController::class, 'storeConfig'])->name('config.add.submit');
        });
        /* End router manager product*/


        /* Router uses for manager platform */
        Route::prefix('platform')->group(function () {
            Route::DELETE('delete',  [PlatFormController::class, 'destroy']);
            Route::post('edit/{slug}', [PlatFormController::class, 'update'])->name('platform.update.submit');
            Route::get('edit/{slug}', [PlatFormController::class, 'show']);
            Route::get('list', [PlatFormController::class, 'index']);
            Route::get('add', [PlatFormController::class, 'create']);
            Route::post('handlesubmitadd', [PlatFormController::class, 'store'])->name('platform.add.submit');
        });
        /* End router manager platform*/

        /* Router uses for manager oder */
        Route::prefix('oder')->group(function () {
            Route::post('detail/{id}/active', [OderController::class, 'updateActive']);
            Route::post('detail/{id}', [OderController::class, 'update']);
        //    Route::DELETE('delete',  [PlatFormController::class, 'destroy']);
           // Route::post('edit/{slug}', [PlatFormController::class, 'update'])->name('platform.update.submit');
            Route::get('detail/{id}', [OderController::class, 'show']);
            Route::get('detail-list/{id}', [OderController::class, 'edit']);
            Route::get('list', [OderController::class, 'index']);
           //Route::get('add', [PlatFormController::class, 'create']);
           // Route::post('handlesubmitadd', [PlatFormController::class, 'store'])->name('platform.add.submit');
        });
        /* End router manager oder*/

        /* Router uses for manager color */
        Route::prefix('color')->group(function () {
            Route::DELETE('delete',  [ColorController::class, 'destroy']);
            Route::get('library/{id}', [ColorController::class, 'showLibrary']);
            Route::post('library/{id}', [ColorController::class, 'uploadLibrary'])->name('library.add.submit');
            Route::post('edit/{id}', [ColorController::class, 'update'])->name('color.update.submit');
            Route::get('edit/{id}', [ColorController::class, 'edit']);
            Route::get('detail/{id}', [ColorController::class, 'show']);
            Route::get('list', [ColorController::class, 'index']);
            Route::get('add/{id}', [ColorController::class, 'create']);
            Route::post('handlesubmitadd', [ColorController::class, 'store'])->name('color.add.submit');
        });
        /* End router manager color*/



        /* Router uses for manager comment*/
        Route::prefix('comment')->group(function () {
            Route::DELETE('delete',  [CommentController::class, 'destroy']);
            Route::get('detail/{slug}', [CommentController::class, 'show']);
            Route::get('list', [CommentController::class, 'index']);
            Route::post('show-comment-children', [CommentController::class, 'showCommentChild']);
            Route::post('show-dashboard', [CommentController::class, 'showDashboard']);
        });
        /* End router manager comment*/


        /* Router uses for manager rating*/
        Route::prefix('rating')->group(function () {
            //  Route::DELETE('delete',  [CommentController::class, 'destroy']);
            Route::get('detail/{slug}', [CommentController::class, 'showRating']);
            Route::get('list', [CommentController::class, 'listRating']);
            Route::post('show-rating', [CommentController::class, 'showRatingMore']);
            //   Route::post('show-dashboard',[CommentController::class, 'showDashboard']);
        });
        /* End router manager rating*/

        /* Router uses for manager brands product */
        Route::prefix('brands')->group(function () {
            Route::DELETE('delete',  [BrandsController::class, 'destroy']);
            Route::post('edit/{id}', [BrandsController::class, 'update'])->name('brands.update.submit');
            Route::get('edit/{id}', [BrandsController::class, 'show']);
            Route::get('list', [BrandsController::class, 'index']);
            Route::get('add', [BrandsController::class, 'create']);
            Route::post('handlesubmitadd', [BrandsController::class, 'store'])->name('brands.add.submit');
        });
        /* End router manager brands product*/

        /* Router uses for manager slide */
        Route::prefix('slide')->group(function () {
            Route::DELETE('delete',  [SlideController::class, 'destroy']);
            Route::post('edit/{id}', [SlideController::class, 'update'])->name('slides.update.submit');
            Route::get('edit/{id}', [SlideController::class, 'show']);
            Route::get('list', [SlideController::class, 'index']);
            Route::get('add', [SlideController::class, 'create']);
            Route::post('handlesubmitadd', [SlideController::class, 'store'])->name('slides.add.submit');
        });
        /* End router manager slide*/

        /* Router uses for manager ship */
        Route::prefix('ship')->group(function () {
            Route::post('get-district', [ShipController::class, 'getDistrict']);
            Route::post('get-wards', [ShipController::class, 'getWard']);
            Route::DELETE('delete',  [ShipController::class, 'destroy']);
            Route::post('edit/{id}', [ShipController::class, 'update'])->name('ship.update.submit');
            Route::get('edit/{id}', [ShipController::class, 'show']);
            Route::get('list', [ShipController::class, 'index']);
            Route::get('add', [ShipController::class, 'create']);
            Route::post('handlesubmitadd', [ShipController::class, 'store'])->name('ship.add.submit');
        });
        /* End router manager ship*/

        /* Router uses for manager value atribute product */
        Route::prefix('value-atribute')->group(function () {
            Route::post('get-type', [ValueController::class, 'getAtribute']);
            Route::DELETE('delete',  [ValueController::class, 'destroy']);
            Route::get('list', [ValueController::class, 'index']);
            Route::get('add', [ValueController::class, 'create']);
            Route::post('handlesubmitadd', [ValueController::class, 'store'])->name('value.add.submit');
        });
        /* End router manager value atribute product*/


        /* Router uses for manager atribute product */
        Route::prefix('atribute')->group(function () {
            Route::post('get-product-type', [AtributeController::class, 'getProductType']);
            Route::DELETE('delete',  [AtributeController::class, 'destroy']);
            Route::DELETE('delete-detail',  [AtributeController::class, 'destroyDetail']);
            Route::get('show-detail/{slug}', [AtributeController::class, 'showDetailPlatForm']);
            Route::post('submit-add', [AtributeController::class, 'addTypePlatForm'])->name('atributePlatForm.add.submit');
            Route::post('edit/{id}', [AtributeController::class, 'update'])->name('atribute.update.submit');
            Route::get('edit/{id}', [AtributeController::class, 'show']);
            Route::get('list-platform', [AtributeController::class, 'showListFlatForm']);
            Route::get('list', [AtributeController::class, 'index']);
            Route::get('add-type', [AtributeController::class, 'showForm']);
            Route::get('add', [AtributeController::class, 'create']);
            Route::post('handlesubmitadd', [AtributeController::class, 'store'])->name('atribute.add.submit');
        });
        /* End router manager atribute product*/

        /* Router uses for manager views mode */
        Route::prefix('view-mode')->group(function () {
            Route::post('get-category', [ViewModeController::class, 'getCategory']);
            Route::post('get-product', [ViewModeController::class, 'getProduct']);
            Route::post('remove-view-product', [ViewModeController::class, 'removeViewProduct']);
            Route::post('get-product-list', [ViewModeController::class, 'getProductList']);
            Route::post('submit-change-view-model-product', [ViewModeController::class, 'changeViewModel']);
            Route::get('list', [ViewModeController::class, 'index']);
            Route::get('add', [ViewModeController::class, 'create']);
        });
        /* End router manager view mode*/
    });
    Route::prefix('/users')->group(function () {
        Route::get('login', [AdminController::class, 'index']);
        Route::post('submit-form', [AdminController::class, 'store'])->name('admin.submit');
    });
});







Route::get('logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    //return redirect('/');
    return (redirect('/'));
});
