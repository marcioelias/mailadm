@extends('layouts.app')

@section('content')

@php
    $period_in = isset($throttlein->period) ?  $throttlein->period : 0;
    $msg_size_in = isset($throttlein->msg_size) ?  $throttlein->msg_size : 0;
    $max_msgs_in = isset($throttlein->max_msgs) ?  $throttlein->max_msgs : 0;
    $max_quota_in = isset($throttlein->max_quota) ?  $throttlein->max_quota : 0;

    $period_out = isset($throttleout->period) ?  $throttleout->period : 0;
    $msg_size_out = isset($throttleout->msg_size) ?  $throttleout->msg_size : 0;
    $max_msgs_out = isset($throttleout->max_msgs) ?  $throttleout->max_msgs : 0;
    $max_quota_out = isset($throttleout->max_quota) ?  $throttleout->max_quota : 0;
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Alterar conta de E-mail', 
                'routeUrl' => route('mailbox.update', $mailbox->username), 
                'method' => 'PUT',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    {{--  Conta  --}}
                    @component('components.input-hidden', ['field' => 'username', 'value' => $mailbox->username]);
                    @endcomponent
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'name',
                                'label' => 'Nome',
                                'inputSize' => 11,
                                'inputValue' => $mailbox->name
                            ],
                            [
                                'type' => 'select',
                                'field' => 'active',
                                'label' => 'Ativada',
                                'inputSize' => 1,
                                'indexSelected' => $mailbox->active,
                                'items' => Array('Não', 'Sim'),
                            ]
                        ] 
                    ])
                    @endcomponent
                    {{--  Nome  --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'conta',
                                'label' => 'Conta',
                                'inputSize' => 8,
                                'inputValue' => $mailbox->username,
                                'disabled' => true
                            ],
                            [
                                'type' => 'text',
                                'field' => 'quota',
                                'label' => 'Quota de Disco',
                                'inputValue' => $mailbox->quota,
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
                                        'inputValue' => $period_out,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'out_msg_size',
                                        'label' => 'Tam. Máx. Mensagem (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => $msg_size_out,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'out_max_msgs',
                                        'label' => 'Núm. Máx. Envios (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => $max_msgs_out,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'out_max_quota',
                                        'label' => 'Máx. Dados Enviados (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => $max_quota_out,
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
                                        'inputValue' => $period_in,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'in_msg_size',
                                        'label' => 'Tam. Máx. Mensagem (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => $msg_size_in,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'in_max_msgs',
                                        'label' => 'Núm. Máx. Recebimentos (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => $max_msgs_in,
                                        'css' => 'text-align-right'
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'in_max_quota',
                                        'label' => 'Máx. Dados Recebidos (0 = Sem controle)',
                                        'inputSize' => 3,
                                        'inputValue' => $max_quota_in,
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
@endsection