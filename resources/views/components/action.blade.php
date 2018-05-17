<?php
    switch($action) {
        case 'show':
            $btn_style = 'btn-success';
            $btn_icon = 'eye-open';
            break;
        case 'edit':
            $btn_style = 'btn-warning';
            $btn_icon = 'edit'; 
            break;
        case 'destroy':
            $btn_style = 'btn-danger';
            $btn_icon = 'remove';
            break;
    }
?>
@php
    $displayField = isset($displayField) ? $displayField : 'name';
@endphp
@if($action == 'destroy')    
    <form id="deleteForm{{$row->id}}" action="{{route($model.'.'.$action, ['$model' => $row])}}" method="POST" style="display: inline">
        <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{'Remover '.ucFirst($model) }}" 
            data-message="Confirma a exclusão do {{ucFirst($model).': '.$row[$displayField]}}?">
            <i class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="{{__('Remover')}}" data-original-title="{{__('Remover')}}"></i>
        </button>
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@else
    <a href="{{route($model.'.'.$action, [$model => $row])}}" class="btn btn-xs {{$btn_style}}"><span class="glyphicon glyphicon-{{$btn_icon}}" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="{{__(ucFirst($action))}}" data-original-title="{{__(ucFirst($action))}}"></span></a>
@endif
