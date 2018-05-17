<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{

    public $captions = array(
        'domain' => 'Domínio');


    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
     {
         $this->middleware('auth');
     }
 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->searchField)) {
            $domains = Domain::where('domain', 'like', '%'.$request->searchField.'%')->orderBy('domain', 'asc')->paginate();
        } else {
            $domains = Domain::orderBy('domain', 'asc')->paginate();
        }
        
        return view('domain.index')->withDomains($domains)->withCaptions($this->captions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('domain.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'domain' => 'required|string',
        ]);

        $aux = new Domain($request->all());
        $aux->save();

        return redirect()->action('DomainController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \mailadm\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \mailadm\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \mailadm\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \mailadm\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        $aux = Domain::find($domain)->first();
        $aux->delete();

        return redirect()->action('DomainController@index');
    }
}
