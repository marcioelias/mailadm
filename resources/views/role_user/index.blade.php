@extends('layouts.app')

@section('content')
    @component('components.table', [
        'captions' => $fields, 
        'rows' => $roleUsers, 
        'model' => 'role_user',
        'tableTitle' => 'Role User Association',
        'displayField' => 'name',
        'actions' => ['edit', 'destroy'],
        ]);
    @endcomponent
@endsection