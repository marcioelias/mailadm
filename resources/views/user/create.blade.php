@extends('layouts.app')

@section('content')

@if(old('name') != '')
    {{--  {{dd(old('name'))}}  --}}
@endif

<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Adicionar Usuário', 
                'routeUrl' => route('user.store'), 
                'method' => 'POST',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'name',
                                'label' => 'Nome',
                                'inputSize' => 8
                            ],
                            [
                                'type' => 'select',
                                'field' => 'role_id',
                                'label' => 'Perfil', 
                                'inputSize' => 4,
                                'items' => $roles,
                                'displayField' => 'display_name',
                                'keyField' => 'id'
                            ]
                        ] 
                    ])
                    @endcomponent
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'email',
                                'label' => 'E-mail',
                                'inputSize' => 6
                            ],
                            [
                                'type' => 'text',
                                'field' => 'email_confirmation',
                                'label' => 'E-mail (Confirmação)',
                                'inputSize' => 6
                            ]
                        ] 
                    ])
                    @endcomponent
                    @component('components.form-group', [
                        'inputs' => [
                            [
                                'type' => 'text',
                                'field' => 'username',
                                'label' => 'Usuário',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'password',
                                'field' => 'password',
                                'label' => 'Senha',
                                'inputSize' => 4
                            ],
                            [
                                'type' => 'password',
                                'field' => 'password_confirmation',
                                'label' => 'Conirmação de Senha',
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