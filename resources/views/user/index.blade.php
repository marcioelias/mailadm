@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @component('components.table', [
            'captions' => $captions, 
            'rows' => $users,
            'model' => 'user',
            'displayField' => 'name',
            'tableTitle' => 'Users',
            'actions' => ['edit', 'destroy']
            ]);
        @endcomponent
    </div>
</div>

@endsection
