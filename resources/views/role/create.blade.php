@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        @component('components.form', [
            'title' => 'New Role', 
            'routeUrl' => route('role.store'), 
            'method' => 'POST',
            'formButtons' => [
                ['type' => 'submit', 'label' => 'Save', 'icon' => 'ok'],
                ['type' => 'button', 'label' => 'Cancel', 'icon' => 'remove']
                ]
            ])
            @section('formFields')
                @component('components.form-group', [
                    'inputs' => [
                        [
                            'type' => 'text',
                            'field' => 'name',
                            'label' => 'Tag',
                            'required' => true,
                            'inputSize' => 6
                        ],
                        [
                            'type' => 'text',
                            'field' => 'display_name',
                            'label' => 'Name',
                            'required' => true,
                            'inputSize' => 6
                        ]
                    ]
                ])
                @endcomponent
                @component('components.form-group', [
                    'inputs' => [
                        [
                            'type' => 'textarea',
                            'field' => 'description',
                            'label' => 'Description',
                        ]
                    ]
                ])
                @endcomponent
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Assigned Permissions <button type="button" id="btnCheckAll" class="btn btn-default btn-xs btn-link">Check All</button><button type="button" id="btnUnCheckAll" class="btn btn-default btn-xs btn-link hidden">Uncheck All</button>
                    </div>
                    <div class="panel-body">
                        @foreach($permissions as $permission)
                            <div class="col-sm-1 col-md-3 col-lg-3">
                                <input type="checkbox" class="permission_checkbox" name="permissions[]" value="{{$permission->id}}">
                                {{$permission->display_name}}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endsection
        @endcomponent
    </div>
    <script>
        $('document').ready(function() {
            var doCheckAll = function checkPermissions() {
                $('.permission_checkbox').prop('checked', true);
                $('#btnCheckAll').toggleClass('hidden');
                $('#btnUnCheckAll').toggleClass('hidden');
            }

            var doUnCheckAll = function unCheckPermissions() {
                $('.permission_checkbox').prop('checked', false);
                $('#btnCheckAll').toggleClass('hidden');
                $('#btnUnCheckAll').toggleClass('hidden');
            }

            $('#btnCheckAll').click(doCheckAll);
            $('#btnUnCheckAll').click(doUnCheckAll);
        })
    </script>
@endsection