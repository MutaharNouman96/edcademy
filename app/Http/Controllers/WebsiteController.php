<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class WebsiteController extends Controller
{
    //

    public function index()
    {
        return view("website.index");
    }

    public function educator_signup()
    {
        if(Auth::check() && Auth::user()->role == 'educator'){
            return redirect()->route("educator.dashboard");
        }
        return view("website.educator-signup");
    }
}
