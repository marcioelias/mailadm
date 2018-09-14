<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidCurrentPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public $captions = [
        'id' => 'ID',
        'username' => 'Usuário',
        'email' => 'E-mail',
        'display_name' => 'Perfil'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->canListarUser()) {
            
            if ($request->searchField) {
                $users = User::with('role_user.role')->paginate();
            } else {
                $users = DB::table('users')
                                ->select('users.*', 'roles.display_name')
                                ->join('role_user', 'role_user.user_id', 'users.id')
                                ->join('roles', 'roles.id', 'role_user.role_id')
                                ->paginate();
            }        

            return View('user.index', [
                'captions' => $this->captions,
                'users' => $users
            ]);
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
        if (Auth::user()->canCadastrarUser()) {
            return View('user.create')->withRoles(Role::all());
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
        if (Auth::user()->canCadastrarUser()) {
            $this->validate($request, [
                'name' => 'required|string',
                'username' => 'required|string|min:5|max:20|unique:users',
                'password' => 'required|string|min:6|max:20|confirmed',
                'email' => 'required|email|confirmed|unique:users'
            ]);
            
            try {
                $user = new User($request->all());
                $user->password = bcrypt($user->password);

                DB::beginTransaction();
                
                if ($user->save()) {
                    try {
                        $roleUser = new RoleUser;
                        $roleUser->user_id = $user->id;
                        $roleUser->role_id = $request->role_id;
                        $roleUser->user_type = User::class;

                        $roleUser->save();
                    } catch (\Exception $x) {
                        Session::flash('error', __('messages.exception', [
                            'exception' => $x->getMessage()
                        ]));
                        return redirect()->back()->withInputs();
                    }
                } else {
                    Session::flash('error',  __('messages.create_error', [
                        'model' => __('models.user'),
                        'name' => $request->name
                    ]));
                    return redirect()->back()->withInputs();
                }          

                DB::commit();

                Session::flash('success', __('messages.create_success', [
                    'model' => __('models.user'),
                    'name' => $user->name
                ]));
                return redirect()->action('UserController@index');

            } catch (\Exception $e) {
                DB::rollback();
                
                Session::flash('error', __('messages.exception', [
                    'exception' => $e->getMessage()
                ]));
                return redirect()->back()->withInputs();
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->canAlterarUser()) {
            return View('user.edit', [
                'user' => $user,
                'roles' => Role::all()
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (Auth::user()->canAlterarUser()) {
            $this->validate($request, [
                'name' => 'required|string',
            ]);
            
            try {            
                $user->name = $request->name;

                DB::beginTransaction();
                
                if ($user->save()) {
                    try {
                        $roleUser = RoleUser::where('user_id', $user->id)->first();
                        $roleUser->role_id = $request->role_id;
                        
                        $roleUser->save();
                    } catch (\Exception $x) {
                        Session::flash('error', __('messages.exception', [
                            'exception' => $x->getMessage()
                        ]));
                    }
                } else {
                    Session::flash('error', __('messages.update_error', [
                        'model' => __('models.user'),
                        'name' => $request->name
                    ]));
                    return redirect()->back()->withInputs();
                }          

                DB::commit();

                Session::flash('success', __('messages.update_success', [
                    'model' => __('models.user'),
                    'name' => $user->name
                ]));
                return redirect()->action('UserController@index');

            } catch (\Exception $e) {
                DB::rollback();
                
                Session::flash('error', __('messages.exception', [
                    'exception' => $e->getMessage()
                ]));
                return redirect()->back()->withInputs();
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (Auth::user()->canExcluirUser()) {
            try {
                if ($user->delete()) {
                    Session::flash('success', 'Usuário '.$user->name.' excluído com sucesso.');
                    
                    return redirect()->action('UserController@index');
                }
            } catch (\Exception $e) {
                switch ($e->getCode()) {
                    case 23000:
                        Session::flash('error', 'Este usuário tem registros associados e não pode ser excluido.');
                        return redirect()->action('UserController@index');
                        break;
                    default:
                        Session::flash('error', 'Oops, ocorreu um erro...'.$e->getMessage());
                        return redirect()->action('UserController@index');
                        break;
                }
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    public function profile() {
        return view('user.profile')->withUser(Auth::user());
    }

    public function showChangePassword() {
        return view('user.change-password')->withUser(Auth::user());
    }

    public function changePassword(Request $request, User $user) {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'current_password' => ['required', new ValidCurrentPassword($user->password)]
        ]);

        try {       
            $user->password = bcrypt($request->password);

            if ($user->save()) {
                Session::flash('success', __('messages.password_change_success', [
                    'user' => $user->name
                ]));
                return redirect()->action('UserController@profile');
            }
        } catch (\Exception $e) {
            Session::flash('error', __('messages.exception', [
                'exception' => $e->getMessage()
            ]));
            return redirect()->back();
        }
    }
}
