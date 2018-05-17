@extends('layouts.app')

@section('content')



<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Adicionar Política', 
                'routeUrl' => route('throttle.store'), 
                'method' => 'POST',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    {{--  Conta  --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'select',
                                'field' => 'account',
                                'label' => 'Conta',
                                'items' => $accounts,
                                'displayField' => 'username',
                                'keyField' => 'username'
                            ]
                        ] 
                    ])
                    @endcomponent

                    {{--  {{-- Tipo / Prioridade / Período --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'kind',
                                'label' => 'Tipo',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'text',
                                'field' => 'priority',
                                'label' => 'Prioridade',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'text',
                                'field' => 'period',
                                'label' => 'Período',
                                'inputSize' => 4
                            ]
                        ]
                    ])
                    @endcomponent

                    {{--  Tamanho Máx. / Máx. Mensagens / Quota  --}}
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'max_size',
                                'label' => 'Tam. Máx. Mensagen',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'text',
                                'field' => 'max_msgs',
                                'label' => 'Núm. Máx. Mensagens',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'text',
                                'field' => 'max_quota',
                                'label' => 'Quota de Mensagens',
                                'inputSize' => 4
                            ]
                        ] 
                    ])
                    @endcomponent 
                @endsection
            @endcomponent
        </div>
    </div>
</div>
@endsection