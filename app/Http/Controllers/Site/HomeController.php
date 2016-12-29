<?php

namespace App\Http\Controllers\Site;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        $categories=Category::with('meals')->get();
        return view('site.index',compact('categories'));
    }

}
