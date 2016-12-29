<?php
namespace App\Http\Controllers\Site;

use App\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
    public function getMeal(Request $request){
        if ($size=Size::with('meal')->find($request['sizeId'])){
            return response()->json(array(
                'success' => true,
                'msg' => 'Succeed.',
                'data' => $size->toArray()
                //'data' => $size->toJson()
            ), 200);
        }
        return response()->json(array(
            'success' => false,
            'msg' => 'Not in database',
        ), 400);

    }
}
