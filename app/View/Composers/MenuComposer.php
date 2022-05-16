<?php

namespace App\View\Composers;
use Illuminate\View\View;
use App\Models\BusinessPlatform;
class MenuComposer
{

  
   public function compose(View $view)
   {
       $dataPlatForm = BusinessPlatform::all();
       $view->with('dataPlatForm', $dataPlatForm);
   }
}
