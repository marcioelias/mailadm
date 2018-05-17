<?php

namespace App\Http\Controllers;

use App\Throttle;
use App\Mailbox;
use Illuminate\Http\Request;

class ThrottleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $accounts = \DB::table('mailbox')->pluck('username');
        $accounts = Mailbox::all();

        return View('throttle.create')->withAccounts($accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $throttle = New Throttle($request->all());

        $throttle->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \mailadm\Throttle  $throttle
     * @return \Illuminate\Http\Response
     */
    public function show(Throttle $throttle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \mailadm\Throttle  $throttle
     * @return \Illuminate\Http\Response
     */
    public function edit(Throttle $throttle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \mailadm\Throttle  $throttle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Throttle $throttle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \mailadm\Throttle  $throttle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Throttle $throttle)
    {
        //
    }
}
