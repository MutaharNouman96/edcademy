<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    //
    public function index()
    {
        return view('crm.educator.payout.index');
    }
}
