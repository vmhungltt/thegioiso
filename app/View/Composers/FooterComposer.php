<?php

namespace App\View\Composers;
use Illuminate\View\View;
use App\Models\Footer;
class FooterComposer
{

   public function compose(View $view)
   {

       $dataFooter = Footer::all();
       $view->with('dataFooter',$dataFooter );
   }
}
