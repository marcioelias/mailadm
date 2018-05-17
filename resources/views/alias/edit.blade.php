@extends('layouts.app')

@section('content')

{{-- 'address', 'goto', 'domain', 'active' --}}

<div class="container-fluid">
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
                    @component('components.input', ['field' => 'address', 'label' => 'Alias', 'value' => $alias->address, 'disabled' => true])
                    @endcomponent
                    @component('components.input', ['field' => 'goto', 'type' => 'textarea', 'label' => 'Endereços', 'value' => $alias->goto, 'required' => true])
                    @endcomponent
                @endsection
            @endcomponent
        </div>
    </div>
</div>

@endsection