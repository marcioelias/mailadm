@php
    $displayField = isset($displayField) ? $displayField : 'name';
@endphp

@if (Session::has('success'))
	<div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('success') }}
    </div>
@endif


<div class="panel panel-default">
    <div class="panel-heading">
       {{--  <div class="contaner container-fluid"> --}}
            <div class="row">
                <div class="col col-sm-12 col-md-12 col-lg-12">
                    <h2>{{__(isset($tableTitle) ? $tableTitle : 'tableTitle não informado...') }}</h2>
                </div>
                <div class="col-sm-10 col-md-11 col-lg-11">
                    <form class="form" method="GET" action="{{ route($model.'.index') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchField" name="searchField" placeholder="{{__('Procurar por...')}}" value="{{isset($_GET['searchField']) ? $_GET['searchField'] : ''}}">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('Search')}}" data-original-title="{{__('Search')}}"></span></button>
                            </span>
                        </div>
                    </div>
                    </div>
                    </form>
                </div>
                <div class="col">
                    <a href="{{ route($model.'.create') }}" class="btn btn-success ">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('Novo')}}" data-original-title="{{__('Novo')}}"></span>
                    </a>
                </div>
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
                    <th class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($captions as $field => $caption)
                        @if(is_array($caption))
                            @if($caption['type'] == 'bool')
                            <td scope="row">{{ __(($row->$field == '1') ? 'Yes' : 'No') }}</td>
                            @endif
                        @else
                            <td scope="row">{{ $row->$field }}</td>
                        @endif
                    @endforeach
                    
                    <td scope="row" class="text-center">
                        @if(is_array($actions))
                            @foreach($actions as $action)
                                @if(is_array($action))
                                    @component($action['custom_action'], ['data' => $row])
                                    @endcomponent
                                @else
                                    @component('components.action', ['action' => $action, 'model' => $model, 'row' => $row, 'displayField' => $displayField])
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
    <div class="panel-footer">
        <div class="text-center">
            {{ $rows->links() }}
        </div> 
    </div>
</div>
<!-- Modal Dialog -->
<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-header-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><strong>{{__('Remover')}}</strong></h4>
      </div>
      <div class="modal-body">
        <p>{{__('Confirma a exclusão do Registro?')}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirm">{{__('Sim')}}</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('Não')}}</button>        
      </div>
    </div>
  </div>
</div>
<!-- Dialog show event handler -->
<script>
  $('#confirmDelete').on('show.bs.modal', function (e) {
      $message = $(e.relatedTarget).attr('data-message');
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