<?php

namespace App\Http\Services\ValueService;

use Illuminate\Support\Str;

use App\Models\Atribute;
use App\Models\Values;
class ValueService
{
    public function whereValues($id){
        return (Values::where('type_id', $id));
    }
    public function insert($request)
    {
        $item = new Values();
        $item->type_id = $request->input('type_id');
        $item->value = $request->input('value');
        $item->updated_at = date('Y-m-d H:i:s');
        $item->created_at =  date('Y-m-d H:i:s');
        $item->save();
      // $name = Atribute::find($request->input('type_id'))->description;
        return (redirect()->back()->with('success', 'Đã thêm giá trị "'.$request->input('value').'" cho thuộc tính "'.Atribute::find($request->input('type_id'))->description.'"'));
    }
    public function getTypes($request){
         $dataItem = Values::where('type_id', $request->input('type_id'))->get();
      /*   foreach($dataItem as $value){
             echo $value->value. "<br/>";
         }*/
      return (view('admin.values.outputvalue', compact('dataItem')));
    }
    public function getValueAll()
    {
        return (Values::all());
    }
    public function getAtributeAll(){
        return (Atribute::all());
    }
    public function where($id)
    {
        return (Atribute::where('id', $id)->firstOrFail());
    }
    public function update($request, $id)
    {
        $item = Atribute::where('id', $id)->firstOrFail();
        $name = $request->input('name');
        $description = $request->input('description');
        $item->name = $name;
        $item->description =  $description;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();
        return (redirect('/admin/atribute/edit/' . $id)->with('success', 'Cập nhật thương hiệu "' . $description . '" Thành công!'));
    }
    public function delete($request)
    {
        $id = $request->input('id');
        return (Values::where('id', $id)->delete());
    }
}
