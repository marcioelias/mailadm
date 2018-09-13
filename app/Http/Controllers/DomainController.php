<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DomainController extends Controller
{

    public $captions = array(
        'domain' => 'Domínio'
    ); 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->canListarDomain()) {
            if (isset($request->searchField)) {
                $domains = Domain::where('domain', 'like', '%'.$request->searchField.'%')->orderBy('domain', 'asc')->paginate();
            } else {
                $domains = Domain::orderBy('domain', 'asc')->paginate();
            }
            
            return view('domain.index')->withDomains($domains)->withCaptions($this->captions);
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->canCadastrarDomain()) {
            return View('domain.create');
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->canCadastrarDomain()) {
            $this->validate($request, [
                'domain' => 'required|string|min:3',
            ]);
        
            try {
                $domain = new Domain($request->all());
                if ($domain->save()) {
                    Session::flash('success', __('messages.create_success', [
                        'model' => __('models.domain'),
                        'name' => $domain->domain
                    ]));
                    return redirect()->action('DomainController@index');
                } else {
                    Session::flash('error', __('messages.create_error', [
                        'model' => __('models.domain'),
                        'name' => $domain->domain
                    ]));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                Session::flash('error', __('messages.exception', [
                    'exception' => $e->getMessage()
                ]));
                return redirect()->back()->withInput();
            }

        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \mailadm\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        if (Auth::user()->canExcluirDomain()) {
            try {
                if ($domain->delete()) {
                    Session::flash('success', __('messages.update_success', [
                        'model' => __('models.domain'),
                        'name' => $domain->domain
                    ]));
                } else {
                    Session::flash('error', __('messages.delete_error', [
                        'model' => __('models.domain'),
                        'name' => $domain->domain
                    ]));
                }

                return redirect()->action('DomainController@index');

            } catch (\Exception $e) {
                switch ($e->getCode()) {
                    case 23000:
                        Session::flash('error', __('messages.fk_exception'));
                        break;
                    default:
                        Session::flash('error', __('messages.exception', [
                            'exception' => $e->getMessage()
                        ]));
                        break;
                }
                return redirect()->action('DomainController@index');
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }
}
