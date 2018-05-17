@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @component('components.table', [
            'captions' => $captions, 
            'rows' => $domains,
            'model' => 'domain',
            'displayField' => 'domain',
            'tableTitle' => 'Domnínios',
            'actions' => ['destroy']
            ]);
        @endcomponent
    </div>
</div>

@endsection
