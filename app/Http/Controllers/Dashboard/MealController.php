<?php
namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Meal;
use App\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function all(){
        $meals=Meal::with('category')->orderBy('id', 'desc')->paginate(15);
        $categories=Category::all();
        return view('dashboard.meals.all',compact('meals','categories'));
    }

    public function create(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png|max:10000|dimensions:max_width=400,max_height=400',
            'price_small' => 'numeric|required_without_all:price_medium,price_large',
            'price_medium' => 'numeric|required_without_all:price_small,price_large',
            'price_large' => 'numeric|required_without_all:price_medium,price_small',
        ]);
        $meal=new Meal;
        $meal->name=$request['name'];
        $meal->category_id=$request['category_id'];
        $meal->description=$request['description'];
        if ($request->hasFile('image')) {
            $meal->image=uploadImage($request->file('image'),'/assets/images/meals/');
        }
        if ($meal->save()){
            // Create new prices
            $prices=[
                'small'=>$request['price_small'],
                'medium'=>$request['price_medium'],
                'large'=>$request['price_large'],
            ];
            foreach ($prices as $key => $value) {
                if ($value) {
                    $size = new Size;
                    $size->meal_id = $meal->id;
                    $size->size = $key;
                    $size->price = $value;
                    $size->save();
                }
            }
            return redirect('dashboard/meals/all')->with('message', 'Meal added successfully!')->with('class', 'alert-success');
        }
        return redirect('dashboard/meals/all')->with('message', 'Failed creating the meal')->with('class', 'alert-danger');
    }

    public function edit($id)
    {
        if ($meal = Meal::find($id)) {
            $categories=Category::all();
            return view('dashboard.meals.edit', compact('meal','categories'));
        }
        return redirect('dashboard/meals/all')->with('message', 'This meal does not exist in DB')->with('class', 'alert-danger');
    }

    public function update(Request $request){
        $rules = array(
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png|max:10000|dimensions:max_width=400,max_height=400',
            'price_small' => 'numeric|required_without_all:price_medium,price_large',
            'price_medium' => 'numeric|required_without_all:price_small,price_large',
            'price_large' => 'numeric|required_without_all:price_medium,price_small',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return response()->json(array(
                'success' => false,
                'msg' => 'Validation Error.',
                'class' => 'alert-danger',
                'errors' => $validator->errors()->all()
            ), 400);
        }

        if ($meal = Meal::find($request['mealId'])) {
            $meal->name = $request['name'];
            $meal->category_id = $request['category_id'];
            $meal->description = $request['description'];
            if ($request->hasFile('image')) {
                $meal->image=uploadImage($request->file('image'),'/assets/images/meals/');
            }
            if ($meal->update()) {
                // Delete old prices
                Size::where(['meal_id'=>$meal->id])->delete();
                // Create new prices
                $prices=[
                    'small'=>$request['price_small'],
                    'medium'=>$request['price_medium'],
                    'large'=>$request['price_large'],
                ];
                foreach ($prices as $key => $value) {
                    if ($value) {
                        $size = new Size;
                        $size->meal_id = $meal->id;
                        $size->size = $key;
                        $size->price = $value;
                        $size->save();
                    }
                }
                return response()->json(array('msg' => 'Meal updated successfully.', 'class' => 'alert-success'), 200);
            }
            return response()->json(array('msg' => 'Failed updating this meal.', 'class' => 'alert-danger'), 200);
        }
        return response()->json(array('msg' => 'This meal did not exist in DB.', 'class' => 'alert-danger'), 200);

    }

    public function delete(Request $request){
        if($meal=Meal::find($request->mealId)){
            $meal->delete();
            return response()->json(array('msg'=> 'Meal deleted successfully.', 'class'=>'alert-success'), 200);
        }
        return response()->json(array('msg'=> 'Failed in deleting.', 'class'=>'alert-danger'), 200);
    }

}
