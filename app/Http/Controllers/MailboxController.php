<?php

namespace App\Http\Controllers;

use App\Mailbox;
use App\Domain;
use App\Throttle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Session;

class MailboxController extends Controller
{
    public $captions = array(
        'username' => 'E-mail', 
        'name' => 'Usuário',
        'quota' => 'Quota (M)');
    
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
            $mailboxes = Mailbox::where('username', 'like', '%'.$request->searchField.'%')->orderBy('username', 'asc')->paginate();            
        } else {
            $mailboxes = Mailbox::orderBy('username', 'asc')->paginate();
        }
        
        return view('mailbox.index')->withMailboxes($mailboxes)->withCaptions($this->captions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $domains = Domain::get();
        return view('mailbox.create')->withDomains($domains);
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
            'name' => 'required|string'
        ]);
        DB::beginTransaction(); 

        $mailbox = new Mailbox();
        $mailbox->username = $request->username . '@' . $request->domain;
        $mailbox->password = '{CRYPT}' . bcrypt($request->password);
        $mailbox->name = $request->name;
        $mailbox->domain = $request->domain;
        $mailbox->local_part = $request->username;
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

        Session::flash('success', 'Conta ' . $request->username . '@' . $request->domain. ' criada com sucesso!');
        
        return redirect()->action('MailboxController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \mailadm\Mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function show(Mailbox $mailbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \mailadm\Mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Mailbox $mailbox)
    {
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
        /* $this->validate([
        ]); */

         /* dd($request->mailbox); */
    /*     $update_data_1  = array(
            'request' => $request,
            'mailbox' => $mailbox
        ); */

        DB::beginTransaction();

        $mailbox->name = $request->mailbox->name;
        $mailbox->quota = $request->mailbox->quota; 
        $mailbox->active = $request->mailbox->active;
        $mailbox->save();

            
        /* Trata da gravação das políticas de envio e recebimeto de mensagens  */
            
        /* Politica de envio de mensagens */
        $throttle = Throttle::updateOrCreate(
            [
                'account' => $request->mailbox->username,
                'kind' => 'outbound'
            ],
            [
                'period' => $request->mailbox->out_period * 60,
                'msg_size' => ($request->mailbox->out_msg_size > 0) ? $request->mailbox->out_msg_size : -1,
                'max_msgs' => ($request->mailbox->out_max_msgs > 0) ? $request->mailbox->out_max_msgs : -1,
                'max_quota' => ($request->mailbox->out_max_quota > 0) ? $request->mailbox->out_max_quota : -1
            ]
        );

        /* Politica de envio de mensagens */
        $throttle = Throttle::updateOrCreate(
            [
                'account' => $request->mailbox->mailbox->username,
                'kind' => 'inbound'
            ],
            [
                'period' => $request->mailbox->in_period * 60,
                'msg_size' => ($request->mailbox->in_msg_size > 0) ? $request->mailbox->in_msg_size : -1,
                'max_msgs' => ($request->mailbox->in_max_msgs > 0) ? $request->mailbox->in_max_msgs : -1,
                'max_quota' => ($request->mailbox->in_max_quota > 0) ? $request->mailbox->in_max_quota : -1
            ]
        );

        DB::commit();

        Session::flash('success', 'Conta ' . $request->mailbox->username . ' alterada com sucesso!');
        
        return redirect()->action('MailboxController@index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \mailadm\Mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mailbox $mailbox)
    {
        $mailbox = Mailbox::find($mailbox)->first();
        $mailbox->delete();

        Session::flash('success', 'Conta ' . $mailbox->username . ' removida com sucesso!');

        return redirect()->action('MailboxController@index');
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
            'password' => 'required|string|min:8|confirmed'
        ]);

        $aux = Mailbox::where('username', $request->username)->first();
        $aux->password = '{CRYPT}' . bcrypt($request->password);
        $aux->save();

        Session::flash('success', 'Senha da conta ' . $request->username . ' alterada com sucesso!');

        return redirect()->action('MailboxController@index');
     }
}
