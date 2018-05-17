@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @component('components.table', [
            'captions' => $captions, 
            'rows' => $aliases,
            'model' => 'alias',
            'displayField' => 'address',
            'tableTitle' => 'Aliases',
            'actions' => ['edit', 'destroy']
            ]);
        @endcomponent
    </div>
</div>

@endsection
