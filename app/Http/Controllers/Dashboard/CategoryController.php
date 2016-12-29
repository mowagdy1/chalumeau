<?php
namespace App\Http\Controllers\Dashboard;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function all(){
        $categories=Category::orderBy('id', 'desc')->paginate(15);
        return view('dashboard.categories.all',compact('categories'));
    }

    public function create(Request $request){
        $this->validate($request, [
            'name' => 'required',
        ]);
        $category=new Category;
        $category->name=$request['name'];
        $category->description=$request['description'];
        if ($category->save()){
            return redirect('dashboard/categories/all')->with('message', 'Category added successfully!')->with('class', 'alert-success');
        }
        return redirect('dashboard/categories/all')->with('message', 'Failed creating the category')->with('class', 'alert-danger');
    }

    public function delete(Request $request){
        if($category=Category::find($request->categoryId)){
            $category->delete();
            return response()->json(array('msg'=> 'Category deleted successfully.', 'class'=>'alert-success'), 200);
        }
        return response()->json(array('msg'=> 'Failed in deleting.', 'class'=>'alert-danger'), 200);
    }

}
