@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @component('components.table', [
            'captions' => $captions, 
            'rows' => $mailboxes,
            'model' => 'mailbox',
            'displayField' => 'username',
            'tableTitle' => 'E-mails',
            'actions' => ['edit', 'destroy']
            ]);
        @endcomponent
    </div>
</div>

@endsection
