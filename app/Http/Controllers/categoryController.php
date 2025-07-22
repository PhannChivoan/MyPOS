<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class categoryController extends Controller
{
    //
    public function create(Request $rq){

        $name = $rq->category;
        Category::create(['name'=>$name]);
        return redirect('/table');
    }
    public function update(Request $rq){
        $cateId = Category::find($rq->id);
        $cateId->name = $rq->name;
        $cateId->save();
        return response()->json(['message' => 'Category updated successfully']);
    
    }
    public function destroy($id){
        $cateId = Category::find($id);
        $cateId->delete();
        
    }
}
