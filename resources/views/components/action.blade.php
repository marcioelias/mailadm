<?php
    switch($action) {
        case 'show':
            $btn_style = 'btn-success';
            $btn_icon = 'eye-open';
            $tooltip = __('strings.Show');
            $permission = 'listar-'.$model;
            break;
        case 'edit':
            $btn_style = 'btn-warning';
            $btn_icon = 'edit'; 
            $tooltip = __('strings.Edit');
            $permission = 'alterar-'.$model;
            break;
        case 'destroy':
            $btn_style = 'btn-danger';
            $btn_icon = 'remove';
            $tooltip = __('strings.Remove');
            $permission = 'excluir-'.$model;
            break;
    }
?>
@php
    $displayField = isset($displayField) ? $displayField : 'name';
    $keyField = isset($keyField) ? $keyField : 'id';
@endphp

@permission($permission)
@if($action == 'destroy')    
    <form id="deleteForm{{$row->id}}" action="{{route($model.'.'.$action, ['$model' => $row->$keyField])}}" method="POST" style="display: inline">
        <span data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" data-original-title="{{$tooltip}}">
             <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{__('Remover').' '.__('models.'.$model) }}" 
                data-message="{{ __('Excluir').' '.__('models.'.$model).': '.$row->$displayField}}?">
                <i class="glyphicon glyphicon-trash"></i>
            </button>
        </span>
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@else
    <span data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" data-original-title="{{$tooltip}}">
        <a href="{{route($model.'.'.$action, [$model => $row->$keyField])}}" class="btn btn-xs {{$btn_style}}"><span class="glyphicon glyphicon-{{$btn_icon}}" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" data-original-title="{{$tooltip}}"></span></a>
    </span>
@endif
@endpermission
