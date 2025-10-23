<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $cats = Category::orderBy('name')->get();
        return view('home', compact('cats'));
    }
}
