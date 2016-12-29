<?php

namespace App\Http\Controllers\Dashboard;

use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function all(){
        $carts=Cart::orderBy('id', 'desc')->paginate(15);
        return view('dashboard.carts.all',compact('carts'));
    }

    public function view($id){
        if ($cart=Cart::with('items')->find($id)){
            return view('dashboard.carts.view',compact('cart'));
        }
        return redirect('dashboard/carts/all')->with('message', 'This cart not in Database')->with('class', 'alert-danger');
    }

    public function delete(Request $request){
        if($cart=Cart::find($request->cartId)){
            $cart->delete();
            return response()->json(array('msg'=> 'Cart deleted successfully.', 'class'=>'alert-success'), 200);
        }
        return response()->json(array('msg'=> 'Failed in deleting.', 'class'=>'alert-danger'), 200);
    }

}
