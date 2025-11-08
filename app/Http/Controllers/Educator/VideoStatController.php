<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoStatController extends Controller
{
    //
    public function index()
    {
        
        return view('crm.educator.video_stats.index');
    }
}
