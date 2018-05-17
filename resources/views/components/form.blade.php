@php
    $btnAlign = isset($btnAlign) ? $btnAlign : 'Right';
    $btnColor = ['submit' => 'success', 'reset' => 'danger', 'button' => 'primary'];
@endphp

@if($title != '')
    <div class="panel-heading"><h3>{{__($title)}}</h3></div>
@endif
<div class="panel-body">
    {{-- <p>Fields with <span class="label label-pill label-success">*</span> are required.</p> --}}
    <form class="form" role="form" method="POST" action="{{$routeUrl}}">
        {{ csrf_field() }}

        @if(isset($method))
            @if($method != 'POST')
                <input name="_method" type="hidden" value="{{$method}}">
            @endif
        @endif

        @yield('formFields')

        <div class="form-group">
            <div class="{{ ($btnAlign == 'Right') ? 'pull-right' : '' }}">
                @if(is_array($formButtons))
                    @foreach($formButtons as $formButton)
                        @if(($formButton['type'] == 'submit') || ($formButton['type'] == 'reset'))
                            <button type="{{$formButton['type']}}" class="btn btn-{{$btnColor[$formButton['type']]}}">
                                {{ __($formButton['label']) }}
                            </button>
                        @else
                            <a href="{{ url()->previous() }}" class="btn btn-{{$btnColor[$formButton['type']]}}">{{ __($formButton['label']) }}</a>
                        @endif
                    @endforeach
                @else
                    <button type="submit" class="btn btn-primary">
                        {{ __($submitLabel) }}
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>