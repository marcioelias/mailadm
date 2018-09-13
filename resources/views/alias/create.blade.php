@extends('layouts.app')

@section('content')
    @if(old())
        {{--  {{//dd($errors)}}  --}}
    @endif
<div class="container-fluid">
    <div id="alias_members_component">
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
                        @component('components.form-group', [
                            'inputs' => [
                                [
                                    'type' => 'text',
                                    'field' => 'address',
                                    'label' => 'Endereço',
                                    'inputSize' => 6,
                                    
                                ],
                                [
                                    'type' => 'text',
                                    'field' => 'name',
                                    'label' => 'Nome',
                                    'inputSize' => 6
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
                                    'defaultNone' => true
                                ],
                                [
                                    'type' => 'select',
                                    'field' => 'accesspolicy',
                                    'label' => 'Política de Acesso',
                                    'inputSize' => 6,
                                    'items' => $aliasAccessPolicies,
                                    'displayField' => 'description',
                                    'keyField' => 'alias_access_policy',
                                    'defaultNone' => true
                                ]
                            ]
                        ])
                        @endcomponent
                        <alias_members 
                            :alias-members-data="{{ json_encode($aliasMembers) }}"
                            :old-data="{{ json_encode(old('membros')) }}"
                            :has-errors="{{ json_encode($errors->has('membros')) }}"
                            :error-msg="{{ json_encode($errors->first('membros')) }}">
                        </alias_members>
                    @endsection
                @endcomponent
            </div>
        </div>
    </div>
    {{--  $('#domain').on('changed.bs.select', buscarEmails);
            if ($('#domain').val()) {
                buscarEmails();
            }  --}}
</div>
@push('bottom-scripts')
    <script src="{{ asset('js/aliasmembers.js') }}"></script>
    <script>
        $(document).ready(function() {
            var buscarEmails = function() {
                var domain = {};

                domain.domain = $('#domain').val();
                domain._token = $('input[name="_token"]').val();

                $.ajax({
                    url: '{{ route("mailbox.json") }}',
                    type: 'POST',
                    data: domain,
                    dataType: 'JSON',
                    cache: false,
                    success: function (data) {
                        $.each(data, function (i, item) {
                            $('#mailbox').append($('<option>', { 
                                value: item.username,
                                text : item.username 
                            }));
                        });
                        
                        @if(old('mailbox'))
                        $('#mailbox').selectpicker('val', {{old('mailbox')}});
                        @endif

                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
        });
    </script>
@endpush
@endsection