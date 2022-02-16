<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Request;

class WebController extends Controller
{
    //
    public function index()
    {
       $products = Product::all();
       return view('web.index', compact('products'));
    }

    public function contactUs()
    {
        return view('web.contact_us');
    }
}
