@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span id="passwordTip" style="font-size: 1.2em">Precisando de uma senha?  <strong id="passwordTipValue"></strong></span> 
            <span id="passwordTipUsed" class="hidden">Senha utilizada e copiada para a área de transferência. (Disponível no Ctrl + V)</span>
            <input type="hidden" value="" id="passwordSuggest">
            <div class="margin-left-15">
                <button class="btn btn-sm btn-primary" type="button" id="generatePasword"><span class="glyphicon glyphicon-refresh"></span>  Sugerir outra</button>
            <button class="btn btn-sm btn-success" type="button" id="usePassword"><span class="glyphicon glyphicon-ok"></span>  Aceitar sugestão</button>
            </div>
        </div>
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Alteração de senha', 
                'routeUrl' => route('mailbox.change_password_store'), 
                'method' => 'POST',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    {{--  Nome / Email  --}}
                    @component('components.input-hidden', ['field' => 'username', 'inputValue' => $mailbox->username])
                    @endcomponent
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'nome',
                                'label' => 'Nome',
                                'inputSize' => 6,
                                'inputValue' => $mailbox->name,
                                'disabled' => true
                            ],
                            [
                                'type' => 'text',
                                'field' => 'conta',
                                'label' => 'Conta',
                                'inputSize' => 6,
                                'inputValue' => $mailbox->username,
                                'disabled' => true
                            ]
                        ] 
                    ])
                    @endcomponent

                    {{--  Senha / Confirmação de senha  --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'password',
                                'field' => 'password',
                                'label' => 'Senha',
                                'inputSize' => 6
                            ],
                            [
                                'type' => 'password',
                                'field' => 'password_confirmation',
                                'name' => 'password_confirmation',
                                'label' => 'Confirmação de senha',
                                'inputSize' => 6
                            ],
                        ] 
                    ])
                    @endcomponent                    
                @endsection
            @endcomponent
        </div>
    </div>
</div>

<script>
    var genPwd = function() {
        var pwdRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");
        var randomPassword = new RandomPassword();
        var pwdSize = [8, 9, 10];
        
        passtext = randomPassword.create(pwdSize[Math.floor(Math.random() * pwdSize.length)],randomPassword.chrLower+randomPassword.chrUpper+randomPassword.chrNumbers+randomPassword.chrSymbols);
        while(!pwdRegex.test(passtext)) {
            passtext = randomPassword.create(pwdSize[Math.floor(Math.random() * pwdSize.length)],randomPassword.chrLower+randomPassword.chrUpper+randomPassword.chrNumbers+randomPassword.chrSymbols);
        }

        return passtext;
    }
    $('documento').ready(function() {
        var pwd = genPwd();
        $('#passwordTipValue').text(pwd);
        $('#passwordSuggest').val(pwd);
    });
    $('#usePassword').click(function(event) {
        $('#password').val($('#passwordSuggest').val());
        $('#password_confirmation').val($('#passwordSuggest').val());
        $('#passwordTip').addClass('hidden');
        $('#passwordTipUsed').removeClass('hidden');
        $('#name').focus();
        $('#usePassword').addClass('hidden');   
        $('#generatePasword').addClass('hidden');   
        copyToClipboard("#passwordTipValue");
    });
    $('#generatePasword').click(function() {
        var pwd = genPwd();
        $('#passwordTipValue').text(pwd);
        $('#passwordSuggest').val(pwd);
    });
</script>

@endsection