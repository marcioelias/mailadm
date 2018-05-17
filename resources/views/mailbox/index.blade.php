@extends('layouts.app')

@section('content')
<!-- Modal para alteração de senha -->
<div class="modal fade text-left" id="changePasswordModal" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
</div>

<div class="container-fluid">
    <div class="row">
        @component('components.table', [
            'captions' => $captions, 
            'rows' => $mailboxes,
            'model' => 'mailbox',
            'displayField' => 'username',
            'tableTitle' => 'E-mails',
            'actions' => [
                'edit', 
                [
                    'custom_action' => 'components.customActions.AccountChangePassword'
                ],
                'destroy']
            ]);
        @endcomponent
    </div>
</div>
@endsection
