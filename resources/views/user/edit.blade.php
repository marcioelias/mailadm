@extends('layouts.app')

@section('content')

{{-- 'address', 'goto', 'domain', 'active' --}}

<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Modificar Usuário', 
                'routeUrl' => route('user.update', $user->id), 
                'method' => 'PUT',
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
                                'inputSize' => 8,
                                'inputValue' => $user->name
                            ],
                            [
                                'type' => 'select',
                                'field' => 'role_id',
                                'label' => 'Perfil', 
                                'inputSize' => 4,
                                'items' => $roles,
                                'displayField' => 'display_name',
                                'keyField' => 'id',
                                'indexSelected' => $user->roles()->first()->id
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
                                'inputSize' => 6,
                                'inputValue' => $user->email,
                                'disabled' => true
                            ],
                            [
                                'type' => 'text',
                                'field' => 'username',
                                'label' => 'Usuário',
                                'inputSize' => 4,
                                'inputValue' => $user->username,
                                'disabled' => true
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