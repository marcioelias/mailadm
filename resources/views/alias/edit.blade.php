@extends('layouts.app')

@section('content')

{{-- 'address', 'goto', 'domain', 'active' --}}

<div class="container-fluid">
    <div id="alias_members_component">
        <div class="row">
            <div class="panel panel-default">
                @component('components.form', [
                    'title' => 'Modificar Alias', 
                    'routeUrl' => route('alias.update', $alias->address), 
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
                                        'field' => 'address',
                                        'label' => 'Endereço',
                                        'inputSize' => 6,
                                        'disabled' => true,
                                        'inputValue' => substr($alias->address, 0, strpos($alias->address, '@'))                                  
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'name',
                                        'label' => 'Nome',
                                        'inputSize' => 6,
                                        'disabled' => true,
                                        'inputValue' => $alias->name
                                    ]
                                ]
                            ])
                            @endcomponent
                            @component('components.form-group', [
                                'inputs' => [
                                    [
                                        'type' => 'select',
                                        'field' => 'domain',
                                        'label' => 'Domínio',
                                        'inputSize' => 6,
                                        'items' => $domains,
                                        'displayField' => 'domain',
                                        'keyField' => 'domain',
                                        'disabled' => true,
                                        'indexSelected' => $alias->domain
                                    ],
                                    [
                                        'type' => 'select',
                                        'field' => 'accesspolicy',
                                        'label' => 'Política de Acesso',
                                        'inputSize' => 6,
                                        'items' => $aliasAccessPolicies,
                                        'displayField' => 'description',
                                        'keyField' => 'alias_access_policy',
                                        'indexSelected' => $alias->accesspolicy
                                    ]
                                ]
                            ])
                            @endcomponent
                            <alias_members 
                                :alias-members-data="{{ json_encode($aliasMembers) }}"
                                :old-data="{{ (old('membros')) ? json_encode(old('membros')) : json_encode($forwardings) }}"
                                :has-errors="{{ json_encode($errors->has('membros')) }}"
                                :error-msg="{{ json_encode($errors->first('membros')) }}">
                            </alias_members>
                        @endsection
                @endcomponent
            </div>
        </div>
    </div>
</div>

@push('bottom-scripts')
    <script src="{{ asset('js/aliasmembers.js') }}"></script>
@endpush

@endsection