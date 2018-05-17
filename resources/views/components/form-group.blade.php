<div class="form-group">      
    <div class="row">
        @foreach($inputs as $input)
            @php
                $input['field'] = isset($input['field']) ? $input['field'] : '';
                $input['inputSize'] = isset($input['inputSize']) ? $input['inputSize'] : '12';
                $input['inputValue'] = isset($input['inputValue']) ? $input['inputValue'] : '';
                $input['css'] = isset($input['css']) ? $input['css'] : '';
                $input['disabled'] = isset($input['disabled']) ? $input['disabled'] : false;
                $input['name'] = isset($input['name']) ? $input['name'] : $input['field'];
                $input['id'] = isset($input['id']) ? $input['id'] : $input['field'];
                $input['displayField'] = isset($input['displayFieldid']) ? $input['displayField'] : $input['field'];
                $input['keyField'] = isset($input['keyField']) ? $input['keyField'] : $input['field'];
                $input['indexSelected'] = isset($input['indexSelected']) ? $input['indexSelected'] : false;
            @endphp
            @if($input['type'] == 'text')
                @component('components.input-text', [
                    'field' => $input['field'],
                    'label' => $input['label'],
                    'inputSize' => $input['inputSize'],
                    'inputValue' => $input['inputValue'],
                    'disabled' => $input['disabled'],
                    'name' => $input['name'],
                    'id' => $input['id'],
                    'css' => $input['css']
                ])
                @endcomponent
            @endif
            @if($input['type'] == 'password')
                @component('components.input-password', [
                    'field' => $input['field'],
                    'label' => $input['label'],
                    'inputSize' => $input['inputSize'],
                    'disabled' => $input['disabled'],
                    'name' => $input['name'],
                    'id' => $input['id'],
                    'css' => $input['css']
                ])
                @endcomponent
            @endif
            @if($input['type'] == 'select')
                @component('components.input-select', [
                    'field' => $input['field'],
                    'label' => $input['label'],
                    'inputSize' => $input['inputSize'],
                    'items' => $input['items'],
                    'displayField' => $input['displayField'],
                    'keyField' => $input['keyField'],
                    'disabled' => $input['disabled'],
                    'name' => $input['name'],
                    'id' => $input['id'],
                    'css' => $input['css'],
                    'inputValue' => $input['inputValue'],
                    'indexSelected' => $input['indexSelected']
                ])
                @endcomponent
            @endif
        @endforeach
    </div>
</div>