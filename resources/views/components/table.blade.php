@php
    $displayField = isset($displayField) ? $displayField : 'name';
    $keyField = isset($keyField) ? $keyField : 'id';
    //$colorLineCondition = isset($colorLineCondition) ? $colorLineCondition : false;
    if (isset($colorLineCondition)) {
        $lineConditionField = $colorLineCondition['field'];
        $lineConditionValue = $colorLineCondition['value'];
        $lineCondicionClass = $colorLineCondition['class'];
    } else {
        $colorLineCondition = false;
    }
    
@endphp

{{--  @if (Session::has('success'))
	<div class="alert alert-success alert-dismissible" id="success-alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('success') }}
    </div>
@endif  --}}


<div class="panel panel-default">
    <div class="panel-heading">
       {{--  <div class="contaner container-fluid"> --}}
            <div class="row">
                <div class="col col-sm-12 col-md-12 col-lg-12">
                    <h3>{{__(isset($tableTitle) ? $tableTitle : 'tableTitle not informed...') }}</h3>
                </div>
                <form id="searchForm" class="form" method="GET" action="{{ route($model.'.index') }}">
                <div class="col-sm-10 col-md-11 col-lg-11">
                    
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchField" name="searchField" placeholder="{{__('strings.TypeToSearch')}}" value="{{isset($_GET['searchField']) ? $_GET['searchField'] : ''}}">
                            <span class="input-group-btn" data-toggle="tooltip" data-placement="top" title="{{__('strings.Search')}}" data-original-title="{{__('Search')}}">
                                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </span>
                        </div>
                    </div>
                    
                </div>
                <div class="col">
                    @permission('cadastrar-'.$model)
                    @if(route::has($model.'.create'))
                    <a href="{{ route($model.'.create') }}" class="btn btn-sm-12 btn-success" data-toggle="tooltip" data-placement="top" title="{{__('strings.New')}}" data-original-title="{{__('New')}}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    @endif
                    @endpermission
                </div>
                @if(isset($searchParms))
                    @component($searchParms)
                    @endcomponent
                @endif
                </form>
            </div>
        {{-- </div>  --}} 
    </div>
    {{--  <div class="panel-body">  --}}
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    @foreach($captions as $field => $caption)
                    @if(is_array($caption))
                    <th>{{__($caption['label'])}}</th>
                    @else
                    <th>{{__($caption)}}</th>
                    @endif
                    @endforeach
                    <th class="text-center">{{ __('strings.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                @if ($colorLineCondition) 
                <tr {{ ($row->$lineConditionField == $lineConditionValue) ? 'class='.$lineCondicionClass : '' }}>
                @else
                <tr {{--  {{(!$row->ativo) ? 'class=danger' : ''}}  --}}>
                @endif
                    @foreach($captions as $field => $caption)
                        @if(is_array($caption))
                            @if($caption['type'] == 'bool')
                            <td scope="row">{{ __(($row->$field == '1') ? 'Yes' : 'No') }}</td>
                            @endif
                            @if($caption['type'] == 'datetime')
                            <td scope="row">{{ date_format(date_create($row->$field), 'd/m/Y H:i:s') }}</td>
                            @endif
                            @if($caption['type'] == 'date')
                            <td scope="row">{{ date_format(date_create($row->$field), 'd/m/Y') }}</td>
                            @endif
                            @if($caption['type'] == 'decimal')
                            <td scope="row"><div align="right">{{ number_format($row->$field, $caption['decimais'], ',', '.') }}</div></td>
                            @endif
                            @if($caption['type'] == 'list')
                            <td scope="row"><div align="right">{{ $caption['values'][$row->$field] }}</div></td>
                            @endif
                        @else
                            <td scope="row">
                                <div {{ is_numeric($row->$field) ? 'align=right' : ''}}>
                                    {{ $row->$field }}
                                </div>
                            </td>
                        @endif
                    @endforeach
                    
                    <td scope="row" class="text-center">
                        @if(is_array($actions))
                            @foreach($actions as $action)
                                @if(is_array($action))
                                    @component($action['custom_action'], ['data' => $row])
                                    @endcomponent
                                @else
                                    @component('components.action', ['action' => $action, 'model' => $model, 'row' => $row, 'displayField' => $displayField, 'keyField'=> $keyField])
                                    @endcomponent
                                @endif
                            @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
   {{--   </div>  --}}
    @if($rows->links() != '')
        <div class="panel-footer">
            <div class="text-center">
                {{ $rows->links() }}
            </div> 
        </div>
    @endif
</div>

<!-- Modal Dialog -->
<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-default">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirm">{{__('Yes')}}</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('No')}}</button>        
      </div>
    </div>
  </div>
</div>
<!-- Dialog show event handler -->
@push('bottom-scripts')
<script>
    $('#confirmDelete').on('show.bs.modal', function (e) {
        $message = $(e.relatedTarget).attr('data-message');
        console.log($message)
        $(this).find('.modal-body p').text($message);
        $title = $(e.relatedTarget).attr('data-title');
        $(this).find('.modal-title').text($title);

        // Pass form reference to modal for submission on yes/ok
        var form = $(e.relatedTarget).closest('form');
        $(this).find('.modal-footer #confirm').data('form', form);
    });

    <!-- Form confirm (yes/ok) handler, submits form -->
    $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
        $(this).data('form').submit();
    });
</script>
@endpush