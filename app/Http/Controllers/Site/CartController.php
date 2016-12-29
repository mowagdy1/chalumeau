<?php

namespace App\Http\Controllers\Site;

use App\Cart;
use App\CartItem;
use App\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function order(Request $request)
    {
        if (Auth::check()) {
            if ($request['theOrder']){
                //dd($request);
                $orderItems=explode(',', $request['theOrder']);
                $cart=new Cart;
                $cart->user_id=Auth::user()->id;
                $cart->save();
                // Adding the items to cart
                for($i=0;$i<count($orderItems);$i=$i+2){
                    $cartItem=new CartItem;
                    $cartItem->cart_id=$cart->id;
                    $cartItem->size_id=$orderItems[$i];
                    $cartItem->quantity=$orderItems[$i+1];
                    $size=Size::find($orderItems[$i]);
                    $cartItem->price=$size->price * ($orderItems[$i+1]);
                    $cartItem->save();
                }
                return back()->with('message', 'Your cart sent successfully.')->with('class', 'alert-success');
            }
            return back()->with('message', 'Your cart is empty! select meals then press the order button.')->with('class', 'alert-danger');
        }
        return redirect('login')->with('message', 'Please login first because only users can make orders.')->with('class', 'alert-danger');
    }
}
