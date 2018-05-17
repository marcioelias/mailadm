@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        @component('components.form', [
            'title' => 'New Role User', 
            'routeUrl' => route('role_user.store'), 
            'method' => 'POST',
            'formButtons' => [
                ['type' => 'submit', 'label' => 'Save', 'icon' => 'ok'],
                ['type' => 'button', 'label' => 'Cancel', 'icon' => 'remove']
                ]
            ])
            @section('formFields')
                @component('components.form-group', [
                    'inputs' => [
                        [
                            'type' => 'select',
                            'field' => 'role_id',
                            'label' => 'Role',
                            'required' => true,
                            'items' => $roles,
                            'inputSize' => 6,
                            'displayField' => 'display_name',
                            'keyField' => 'id',
                            'liveSearch' => true
                        ],
                        [
                            'type' => 'select',
                            'field' => 'user_id',
                            'label' => 'User',
                            'required' => true,
                            'items' => $users,
                            'inputSize' => 6,
                            'displayField' => 'name',
                            'keyField' => 'id',
                            'liveSearch' => true
                        ]
                    ]
                ])
                @endcomponent
            @endsection
        @endcomponent
    </div>
@endsection