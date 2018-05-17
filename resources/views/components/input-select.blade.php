{{--  {{dd($items)}}  --}}
@php
    $inputSize = isset($inputSize) ? '-'.$inputSize : '-12';
    $items = isset($items) ? $items : false;
    $disabled = isset($disabled) ? $disabled : false;
    $autofocus = isset($autofocus) ? $autofocus : false;
    $required = isset($required) ? $required : false;
    $css = isset($css) ? $css : '';
    $indexSelected = isset($indexSelected) ? $indexSelected : false;
@endphp
<div class="col col-sm col-md{{$inputSize}} col-lg{{$inputSize}} {{ $errors->has($field) ? ' has-error' : '' }}">
    @if(isset($label))
        @component('components.label', ['label' => $label, 'field' => $field, 'required' => $required])
        @endcomponent
    @endif  
    <select class="form-control {{$css}}" id="{{$id}}" name="{{$name}}" {{ $required ? 'required' : '' }}  {{ $autofocus ? 'autofocus' : '' }} {{ $disabled ? 'disabled="disabled"' : '' }}>
        @if(isset($items))
            @if(is_array($items))
                @for ($i = 0; $i < count($items); $i++)
                    <option value="{{ $i }}" {{($i==$indexSelected) ? 'selected=selected' : ''}}>{{ $items[$i] }}</option>
                @endfor
            @else
                @foreach($items as $item)
                    <option value="{{ $item->$keyField }}" @if($item->$keyField==$indexSelected) {{'selected="selected"'}} @endif>{{ $item->$displayField }}</option>
                @endforeach
            @endif
        @endif
    </select>

    @if ($errors->has($field))
        <span class="help-block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>