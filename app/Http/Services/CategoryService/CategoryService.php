<?php

namespace App\Http\Services\CategoryService;

use Illuminate\Support\Str;
use App\Models\Category;
use App\Helper\Helper;

class CategoryService
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
    public function insert($request)
    {
        //business-platform
        $businessPlatform = $request->input('business-platform');
        $name = $request->input('name');
        $category = $request->input('category');
        $description = $request->input('description');
        $content = $request->input('content');
        $active = $request->input('active');
        $slug = Str::slug($name, '-');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $insert = Category::create([
            'name' => $name,
            'parent_id' => $category,
            'description' => $description,
            'content' => $content,
            'slug' => $slug,
            'active' => $active,
            'business_platform_id' => $businessPlatform,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        return (redirect()->back()->with('success', 'Đã thêm danh mục ' . $name));
    }
    public function getCategoryParent()
    {
        $result  = Category::where('parent_id', 0)
            ->get();
        return ($result);
    }
    public function getCategoryAll()
    {
        $result  = Category::all();
        $listItem = $this->createMenuTree($result, 0, 0);
        return ($listItem);
    }
    public function delete($request){
     $id = $request->input('id');
     return( Category::where('id',$id)->orWhere('parent_id', $id)->delete()) ;
    }
    public function where($slug){
        $result  = Category::where('slug', '=', $slug)->firstOrFail();
        return ($result);
    }
    public function update($request, $slug){

        $item = Category::where('slug', $slug)->first();
        $businessPlatform = $request->input('business-platform');
        $name = $request->input('name');
        $category = $request->input('category');
        $description = $request->input('description');
        $content = $request->input('content');
        $active = $request->input('active');
        $slug = Str::slug($name, '-');
        if ($active == null) {
            $active = 0;
        } else {
            $active = 1;
        }
        $item->business_platform_id = $businessPlatform;
        $item->name = $name;
        $item->content = $content;
        $item->parent_id = $category;
        $item->description = $description;
        $item->active = $active;
        $item->slug = $slug;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/category/edit/'. $slug)->with('success', 'Cập nhật danh mục "'.$name.'" Thành công!'));
        // 'updated_at' => date('Y-m-d H:i:s'),
       // echo $request->name;
       // dd($result);
    }
}
