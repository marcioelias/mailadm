<?php

namespace App\Http\Controllers;

use App\Alias;
use App\Domain;
use App\Mailbox;
use App\Forwarding;
use App\AliasAccessPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AliasController extends Controller
{

    public $captions = array(
        'address' => 'Alias',
        'name' => 'Descrição', 
        'domain' => 'Domínio');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->canListarAlias()) {
            if (isset($request->searchField)) {
                $aliases = Alias::where('address', 'like', '%'.$request->searchField.'%')
                                    ->orWhere('name', 'like', '%'.$request->searchField.'%')
                                    ->orderBy('address', 'asc')
                                    ->paginate();
            } else {
                $aliases = Alias::orderBy('address', 'asc')->paginate();
            }
            
            return view('alias.index')->withAliases($aliases)->withCaptions($this->captions);
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
        if (Auth::user()->canCadastrarAlias()) {
            $domains = Domain::all();
            $aliasAccessPolicies = AliasAccessPolicy::all();

            return view('alias.create', [
                'domains' => $domains,
                'aliasAccessPolicies' => $aliasAccessPolicies,
                'aliasMembers' => Mailbox::select('username')->where('active', true)->get()
            ]);
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
        if (Auth::user()->canCadastrarAlias()) {
            $this->validate($request, [
                'address' => 'required|string',
                'name' => 'required|string',
                'domain' => 'required|string|min:1',
                'membros' => 'required|array|min:1',
                'accesspolicy' => 'required|string|min:1'
            ]);

            try {

                DB::beginTransaction();
                                
                $alias = new Alias($request->all());
                $alias->address = $alias->address . '@' . $alias->domain; 

                if ($alias->save()) {
                    foreach($request->membros as $item) {
                        $forwarding = new Forwarding();
                        $forwarding->address = $alias->address;
                        $forwarding->forwarding = $item['forwarding'];
                        $forwarding->domain = $alias->domain;
                        $forwarding->dest_domain = $alias->domain;
                        $forwarding->is_list = 0;
                        $forwarding->is_forwarding = 1;
                        $forwarding->is_alias = 0;
                        $forwarding->active = ($item['itemActive']) ? 1 : 0;
                        $forwarding->is_maillist = 0;

                        if (!$forwarding->save()) {
                            throw new Exception('Erro ao salvar endereços do Alias');
                        }
                    }

                    DB::commit();
                    Session::flash('success', __('messages.create_success', [
                        'model' => __('models.alias'),
                        'name' => $alias->address
                    ]));
                    return redirect()->action('AliasController@index');
                } else {
                    
                    DB::rollback();
                    
                    Session::flash('error', __('messages.create_error', [
                        'model' => __('models.alias'),
                        'name' => $alias->address
                    ]));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {

                DB::rollback();

                switch ($e->getCode()) {
                    case 23505:
                        Session::flash('error', __('messages.duplicated_record', [
                            'nome' => $alias->address
                        ]));
                        break;
                    default:
                        Session::flash('error', __('messages.exception', [
                            'exception' => $e->getMessage()
                        ]));
                        break;
                }
                
                return redirect()->back()->withInput();
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \mailadm\Alias  $alias
     * @return \Illuminate\Http\Response
     */
    public function edit(Alias $alias)
    {
        if (Auth::user()->canAlterarAlias()) {
            $domains = Domain::all();
            $aliasAccessPolicies = AliasAccessPolicy::all();
            $forwardings = Forwarding::where('address', $alias->address)
                            ->orderBy('forwarding', 'asc')
                            ->get();

            return view('alias.edit', [
                'alias' => $alias,
                'forwardings' => $forwardings,
                'domains' => $domains,
                'aliasAccessPolicies' => $aliasAccessPolicies,
                'aliasMembers' => Mailbox::select('username')->where('active', true)->get()
            ]);
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
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
        if (Auth::user()->canAlterarAlias()) {
            $this->validate($request, [
                'membros' => 'required|array|min:1',
                'accesspolicy' => 'required|string|min:1'
            ]);

            try {

                DB::beginTransaction();
                                
                //$alias->address = $alias->address . '@' . $alias->domain; 

                if ($alias->save()) {
                    
                    $alias->forwardings()->delete();

                    foreach($request->membros as $item) {
                        $forwarding = new Forwarding();
                        $forwarding->address = $alias->address;
                        $forwarding->forwarding = $item['forwarding'];
                        $forwarding->domain = $alias->domain;
                        $forwarding->dest_domain = $alias->domain;
                        $forwarding->is_list = 0;
                        $forwarding->is_forwarding = 1;
                        $forwarding->is_alias = 0;
                        $forwarding->active = ($item['itemActive'] == 'false') ? 0 : 1;
                        $forwarding->is_maillist = 0;

                        if (!$forwarding->save()) {
                            throw new Exception('Erro ao salvar endereços do Alias');
                        }
                    }

                    DB::commit();

                    Session::flash('success', __('messages.update_success', [
                        'model' => __('models.alias'),
                        'name' => $alias->address
                    ]));

                    return redirect()->action('AliasController@index');
                } else {
                    
                    DB::rollback();
                    
                    Session::flash('error', __('messages.update_error', [
                        'model' => __('models.alias'),
                        'name' => $alias->address
                    ]));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {

                DB::rollback();

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
     * @param  \mailadm\Alias  $alias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alias $alias)
    {
        if (Auth::user()->canExcluirAlias()) {
            try {
                
                DB::beginTransaction();
                if ($alias->forwardings()->delete()) {
                    if ($alias->delete()) {
                        DB::commit();
                        Session::flash('success', __('messages.update_success', [
                            'model' => __('models.alias'),
                            'name' => $alias->address
                        ]));
                        return redirect()->action('AliasController@index');
                    } else {
                        DB::rollback();
                        Session::flash('error', __('messages.delete_error', [
                            'model' => __('models.alias'),
                            'name' => $alias->address
                        ]));
                        return redirect()->action('AliasController@index');
                    }
                } else {
                    DB::rollback();
                    Session::flash('error', __('messages.delete_error', [
                        'model' => __('models.alias'),
                        'name' => $alias->address
                    ]));
                    return redirect()->action('AliasController@index');
                } 
            } catch (\Exception $e) {
                DB::rollback();
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
                return redirect()->action('AliasController@index');
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }
}
