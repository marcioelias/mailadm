<?php

namespace App\Http\Controllers;

use App\Alias;
use App\Domain;
use Illuminate\Http\Request;

class AliasController extends Controller
{

    public $captions = array(
        'address' => 'Alias', 
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
            $aliases = Alias::where('address', 'like', '%'.$request->searchField.'%')->orderBy('address', 'asc')->paginate();
        } else {
            $aliases = Alias::orderBy('address', 'asc')->paginate();
        }
        
        return view('alias.index')->withAliases($aliases)->withCaptions($this->captions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $domains = Domain::get();

        return view('alias.create')->withDomains($domains);
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
            'address' => 'required|string',
            'goto' => 'required|string',
            'domain' => 'required|string'
        ]);

        $aux = new Alias($request->all());
        $aux->address .= '@' . $aux->domain; 
        $aux->save();

        return redirect()->action('AliasController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \mailadm\Alias  $alias
     * @return \Illuminate\Http\Response
     */
    public function show(Alias $alias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \mailadm\Alias  $alias
     * @return \Illuminate\Http\Response
     */
    public function edit(Alias $alias)
    {
        return view('alias.edit')->withAlias($alias);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \mailadm\Alias  $alias
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alias $alias)
    {
        $this->validate($request, [
            'goto' => 'required|string'
        ]);

        $aux = Alias::find($alias)->first();
        
        $aux->goto = $request->goto;

        $aux->save();

        return redirect()->action('AliasController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \mailadm\Alias  $alias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alias $alias)
    {
        $aux = Alias::find($alias)->first();
        $aux->delete();

        return redirect()->action('AliasController@index');
    }
}
