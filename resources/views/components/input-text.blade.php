@php
    $inputSize = isset($inputSize) ? '-'.$inputSize : '-12';
    $items = isset($items) ? $items : false;
    $disabled = isset($disabled) ? $disabled : false;
    $autofocus = isset($autofocus) ? $autofocus : false;
    $required = isset($required) ? $required : false;
    $css = isset($css) ? $css : '';
    $div_css = isset($div_css) ? $div_css : '';
    $vModel = isset($vModel) ? $vModel : false;
    $readOnly = isset($readOnly) ? $readOnly : false;
@endphp

{{--  {{dd($inputValue)}}  --}}
<div class="col col-sm col-md{{$inputSize}} col-lg{{$inputSize}} {{ $errors->has($field) ? ' has-error' : '' }} {{$div_css}}">
    @if(isset($label))
        @component('components.label', ['label' => $label, 'field' => $field, 'required' => $required])
        @endcomponent
    @endif  

    <input type="text" class="form-control {{$css}}" {{ ($vModel) ? 'v-model='.$vModel : '' }} name="{{$name}}" id="{{$id}}" value="{{ isset($inputValue) ? $inputValue : old($field) }}" {{ $required ? 'required' : '' }}  {{ $autofocus ? 'autofocus' : '' }} {{ $disabled ? 'disabled="disabled"' : '' }} {{ $readOnly ? 'readonly' : '' }}>

    @if ($errors->has($field))
        <span class="help-block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>