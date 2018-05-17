@extends('layouts.app')

@section('content')

{{-- 'address', 'goto', 'domain', 'active' --}}


<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">
            @component('components.form', [
                'title' => 'Adicionar Domínio', 
                'routeUrl' => route('domain.store'), 
                'method' => 'POST',
                'formButtons' => [
                    ['type' => 'submit', 'label' => 'Salvar'],
                    ['type' => 'button', 'label' => 'Cancelar']
                    ]
                ])
                @section('formFields')
                    @component('components.input', ['field' => 'domain', 'label' => 'Domínio', 'required' => true]);
                    @endcomponent
                @endsection
            @endcomponent
        </div>
    </div>
</div>

@endsection