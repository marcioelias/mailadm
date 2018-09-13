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
                'title' => 'Adicionar Conta de E-mail', 
                'routeUrl' => route('mailbox.store'), 
                'method' => 'POST',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    {{--  Nome  --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'name',
                                'label' => 'Nome'
                            ]
                        ] 
                    ])
                    @endcomponent

                    {{-- Conta / Dominio --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'username',
                                'label' => 'Conta',
                                'inputSize' => 6
                            ],
                            [
                                'type' => 'select',
                                'field' => 'domain',
                                'label' => 'Domínio',
                                'inputSize' => 6,
                                'items' => $domains,
                                'displayField' => 'domain',
                                'keyField' => 'domain'
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
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'password',
                                'field' => 'password_confirmation',
                                'label' => 'Confirmação de senha',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'text',
                                'field' => 'quota',
                                'label' => 'Quota de Disco',
                                'inputSize' => 4,
                                'css' => 'text-align-right'
                            ]
                        ] 
                    ])
                    @endcomponent

                    {{--  Painel de políticas de envio de mensagens  --}}
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Políticas de Envio de E-mails</strong>
                        </div>
                        <div class="panel-body">
                            @component('components.input-hidden', ['field' => 'out_priority', 'value' => 100])
                            @endcomponent
                            {{--  Senha / Confirmação de senha  --}}
                            @component('components.form-group', [
                                'inputs' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'out_period',
                                        'label' => 'Período (Minutos)',
                                        'inputSize' => 3,
                                        'inputValue' => 60,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'out_msg_size',
                                        'label' => 'Tam. Máx. Mensagem (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => 0,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'out_max_msgs',
                                        'label' => 'Núm. Máx. Envios (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => 50,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'out_max_quota',
                                        'label' => 'Máx. Dados Enviados (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => 0,
                                        'css' => 'text-align-right'
                                    ]
                                ] 
                            ])
                            @endcomponent 
                        </div>
                    </div>

                    {{--  Painel de políticas de recebimento de mensagens  --}}
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Políticas de Recebimento de E-mails</strong>
                        </div>
                        <div class="panel-body">
                            {{--  Senha / Confirmação de senha  --}}
                            @component('components.form-group', [
                                'inputs' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'in_period',
                                        'label' => 'Período (Minutos)',
                                        'inputSize' => 3,
                                        'inputValue' => 0,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'in_msg_size',
                                        'label' => 'Tam. Máx. Mensagem (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => 0,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'in_max_msgs',
                                        'label' => 'Núm. Máx. Recebimentos (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => 0,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'in_max_quota',
                                        'label' => 'Máx. Dados Recebidos (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => 0,
                                        'css' => 'text-align-right'
                                    ]
                                ] 
                            ])
                            @endcomponent 
                        </div>
                    </div>
                    
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