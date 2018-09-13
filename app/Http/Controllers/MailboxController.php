<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Mailbox;
use App\Throttle;
use App\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

class MailboxController extends Controller
{
    public $captions = array(
        'username' => 'E-mail', 
        'name' => 'Usuário',
        'quota' => 'Quota (M)');
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->canListarMailbox()) {
            if (isset($request->searchField)) {
                $mailboxes = Mailbox::where('username', 'like', '%'.$request->searchField.'%')->orderBy('username', 'asc')->paginate();            
            } else {
                $mailboxes = Mailbox::orderBy('username', 'asc')->paginate();
            }
            
            return view('mailbox.index')->withMailboxes($mailboxes)->withCaptions($this->captions);
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
        if (Auth::user()->canCadastrarMailbox()) {
            return view('mailbox.create')->withDomains(Domain::all());
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
        if (Auth::user()->canCadastrarMailbox()) {
            $this->validate($request, [
                'name' => 'required|string',
                'username' => 'required|string',
                'password' => [ 'confirmed', new Password ]
            ]);
            
            try {
                DB::beginTransaction(); 

                $mailbox = new Mailbox();
                $mailbox->username = $request->username . '@' . $request->domain;
                $mailbox->password = '{CRYPT}' . bcrypt($request->password);
                $mailbox->name = $request->name;
                $mailbox->domain = $request->domain;
                //$mailbox->local_part = $request->username;
                $mailbox->maildir = $this->getMailDir($mailbox);
                $mailbox->quota = $request->quota;    
                $mailbox->save();

                
                /* Trata da gravação das políticas de envio e recebimeto de mensagens para a conta criada */
                $throttle = new Throttle();
                $throttle->account = $request->username . '@' . $request->domain;
                $throttle->priority = 100;      /* priority hardcoded for email policies */

                /* Politica de envio de mensagens para a conta de e-mail criada */
                $throttle->kind = 'outbound';
                $throttle->period = $request->out_period * 60;
                $throttle->msg_size = ($request->out_msg_size > 0) ? $request->out_msg_size : -1;
                $throttle->max_msgs = ($request->out_max_msgs > 0) ? $request->out_max_msgs : -1;
                $throttle->max_quota = ($request->out_max_quota > 0) ? $request->out_max_quota : -1;

                /* Salva a política de envio */
                $throttle->save();

                /* Politica de recebimento de mensagens para a conta de e-mail criada */
                $throttle->kind = 'inbound';
                $throttle->period = $request->in_period * 60;
                $throttle->msg_size = ($request->in_msg_size > 0) ? $request->in_msg_size : -1;
                $throttle->max_msgs = ($request->in_max_msgs > 0) ? $request->in_max_msgs : -1;
                $throttle->max_quota = ($request->in_max_quota > 0) ? $request->in_max_quota : -1;

                /* Salva a política de recebimento */
                $throttle->save();

                DB::commit();

                Session::flash('success', __('messages.create_success', [
                    'model' => __('models.mailbox'),
                    'name' => $request->username . '@' . $request->domain
                ]));
                
                return redirect()->action('MailboxController@index');
            } catch (\Exception $e) {
                DB::rollback();
                
                switch ($e->getCode()) {
                    case 23505:
                        Session::flash('error', __('messages.duplicated_record', [
                            'nome' => $mailbox->username
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
            return redirect()->back()->withInput();
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \mailadm\Mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Mailbox $mailbox)
    {
        if (Auth::user()->canAlterarMailbox()) {
            $throttlein = Throttle::where(['account' => $mailbox->username, 'kind' => 'inbound'])->first(); 
            if ($throttlein) {
                $throttlein->period = ($throttlein->period > 0) ? $throttlein->period / 60 : 0;
                $throttlein->msg_size = ($throttlein->msg_size < 0) ? 0 :  $throttlein->msg_size;
                $throttlein->max_msgs = ($throttlein->max_msgs < 0) ? 0 :  $throttlein->max_msgs;
                $throttlein->max_quota = ($throttlein->max_quota < 0) ? 0 :  $throttlein->max_quota;
            }

            $throttleout = Throttle::where(['account' => $mailbox->username, 'kind' => 'outbound'])->first();
            if ($throttleout) {
                $throttleout->period = ($throttleout->period > 0) ? $throttleout->period / 60 : 0;
                $throttleout->msg_size = ($throttleout->msg_size < 0) ? 0 :  $throttleout->msg_size;
                $throttleout->max_msgs = ($throttleout->max_msgs < 0) ? 0 :  $throttleout->max_msgs;
                $throttleout->max_quota = ($throttleout->max_quota < 0) ? 0 :  $throttleout->max_quota;
            }

            return View('mailbox.edit')->withMailbox($mailbox)->withThrottlein($throttlein)->withThrottleout($throttleout);
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \mailadm\Mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mailbox $mailbox)
    {
       
       if (Auth::user()->canAlterarMailbox()) {
            /* $this->validate([
            ]); */

            /* dd($request->mailbox); */
        /*     $update_data_1  = array(
                'request' => $request,
                'mailbox' => $mailbox
            ); */

            try {
                DB::beginTransaction();

                //dd($request->all());

                $mailbox->fill($request->all());
                //$mailbox->username = $request->username . '@' . $request->domain;
    
                $mailbox->save();

                    
                /* Trata da gravação das políticas de envio e recebimeto de mensagens  */
                    
                /* Politica de envio de mensagens */
                $throttle = Throttle::updateOrCreate(
                    [
                        'account' => $mailbox->username, 
                        'kind' => 'outbound'
                    ],
                    [
                        'period' => $request->out_period * 60,
                        'msg_size' => ($request->out_msg_size > 0) ? $request->out_msg_size : -1,
                        'max_msgs' => ($request->out_max_msgs > 0) ? $request->out_max_msgs : -1,
                        'max_quota' => ($request->out_max_quota > 0) ? $request->out_max_quota : -1
                    ]
                );

                /* Politica de recebimento de mensagens */
                $throttle = Throttle::updateOrCreate(
                    [
                        'account' => $mailbox->username,
                        'kind' => 'inbound'
                    ],
                    [
                        'period' => $request->in_period * 60,
                        'msg_size' => ($request->in_msg_size > 0) ? $request->in_msg_size : -1,
                        'max_msgs' => ($request->in_max_msgs > 0) ? $request->in_max_msgs : -1,
                        'max_quota' => ($request->in_max_quota > 0) ? $request->in_max_quota : -1
                    ]
                );

                DB::commit();

                Session::flash('success', __('messages.update_success', [
                    'model' => __('models.mailbox'),
                    'name' => $mailbox->username
                ]));
                
                return redirect()->action('MailboxController@index');

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
     * @param  \mailadm\Mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mailbox $mailbox)
    {
        if (Auth::user()->canExcluirMailbox()) {
            try {
                if ($mailbox->delete()) {
                    Session::flash('success', __('messages.delete_success', [
                        'model' => __('models.mailbox')
                    ]));
                    return redirect()->action('MailboxController@index');
                } else {
                    Session::flash('error', __('messages.cant_delete', [
                        'model' => __('models.mailbox'),
                        'name' => $mailbox->username
                    ]));
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                Session::flash('error', __('messages.exception', [
                    'exception' => $e->getMessage()
                ]));
                return redirect()->back();
            }
        } else {
            Session::flash('error', __('messages.access_denied'));
            return redirect()->back();
        }
    }

    private function getMailDir(Mailbox $mailbox) {
        return $mailbox->domain . '/' . $mailbox->username[0] . '/' . $mailbox->username[1] . '/' . $mailbox->username[2] . '/' . $mailbox->local_part . '-' . date('Y-m-d-H-i-s');
    }

    /** 
     * Metodo mostrar um form para alteração de senha de conta  
     *
     */
     public function change_password_show(Request $request) {
        $mailbox = Mailbox::where('username', $request->mailbox)->first();
        return View('mailbox.change-password')->withMailbox($mailbox);
     }

    /** 
     * Metodo alterar a senha de uma conta  
     *
     */
     public function change_password_store(Request $request, Mailbox $mailbox) {
        $this->validate($request, [
            'password' => [ 'confirmed', new Password ]
        ]);

        $aux = Mailbox::where('username', $request->username)->first();
        $aux->password = '{CRYPT}' . bcrypt($request->password);
        $aux->save();

        Session::flash('success', 'Senha da conta ' . $request->username . ' alterada com sucesso!');

        return redirect()->action('MailboxController@index');
     }

    public function getMailboxesJson(Request $request) {
        $mailboxes = Mailbox::select(['username', 'domain'])
                            ->where('domain', $request->domain)
                            ->orderBy('username', 'asc')
                            ->get();
        
        return response()->json($mailboxes);
    }
}
