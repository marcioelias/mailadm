@extends('layouts.app')

@section('content')

{{-- 'address', 'goto', 'domain', 'active' --}}


<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Adicionar Alias', 
                'routeUrl' => route('alias.store'), 
                'method' => 'POST',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    @component('components.input', ['field' => 'domain', 'type' => 'select', 'label' => 'Domínio', 'items' => $domains, 'fieldDisplay' => 'domain']);
                    @endcomponent
                    @component('components.input', ['field' => 'address', 'label' => 'Alias (sem o domínio)', 'required' => true])
                    @endcomponent
                    @component('components.input', ['field' => 'goto', 'type' => 'textarea', 'label' => 'Endereços', 'required' => true])
                    @endcomponent
                @endsection
            @endcomponent
        </div>
    </div>
</div>

@endsection