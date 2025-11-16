<?php

namespace App\Http\Controllers\Educator\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        // Fetch all settings via relationships
        $profile        = $user->profileSetting;
        $security       = $user->securitySetting;
        $payment        = $user->paymentSetting;
        $methods        = $user->paymentMethods;
        $availability   = $user->availability;
        $notifications  = $user->notificationSetting;
        $privacy        = $user->privacy;
        $verification   = $user->verification;
        $connections    = $user->connections;
        $preferences    = $user->preferences;

        return view('educator.settings', compact(
            'user',
            'profile',
            'security',
            'payment',
            'methods',
            'availability',
            'notifications',
            'privacy',
            'verification',
            'connections',
            'preferences'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
