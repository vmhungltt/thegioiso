<?php
  namespace App\Helper;
  class Helper {
  /*  public static function createMenuTree($menuList, $parent_id, $lever){
        $menuTree = array();
        foreach($menuList as $key => $menu){
          if($menu->parent_id == $parent_id){
             $menu['lever'] = $lever;
             $menuTree[] = $menu;
           unset($menuList[$key]);
             $children = self::createMenuTree($menuList, $menu->id, $lever + 1);
             $menuTree = array_merge($menuTree, $children);
          }
        }
        return ($menuTree);
      }*/
      public function test(){
          echo 'run here';
      }
  }
