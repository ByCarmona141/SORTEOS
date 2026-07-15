<div class="col-md-{{ $colSize }} {{  $classDiv }}" id="{{ $idDiv }}">
    @if($required)
        <font color="red">*</font>
    @endif
    <label for="{{ $id }}" class="form-label">{{ $label }}:</label>
    @switch($type)
        @case('text')
        @case('date')
        @case('datetime-local')
        @case('number')
        @case('email')
        @case('password')
        @case('checkbox')
            <input type="{{ $type }}" class="form-control {{ $classForm }}" id="{{ $id }}" name="{{ $name }}"
                   value="{{ $value }}"
                   placeholder="{{ $placeholder }}" {{ $focused ? 'autofocus' : '' }} {{ $readonly ? 'readonly disabled' : '' }}
                {{ $dataOptions }}>
            @break
        @case('file')
            <input type="{{ $type }}" class="form-control {{ $classForm }}" id="{{ $id }}" name="{{ $name }}"
                   accept="{{ $accept }}">
            @break
        @case('textarea')
            <textarea class="form-control {{ $classForm }}" id="{{ $id }}" name="{{ $name }}"
                      rows="5">{{ old($id, $value) }}</textarea>
            @break
        @case('select')
            <select data-live-search="true" class="form-control selectpicker show-tick {{ $classForm }}"
                    name="{{ $name }}" id="{{ $id }}"
                    data-size="5">
                <option value="" selected>{{ $defaultTextOption }}</option>
                @foreach($optionsExtraFirst as $option)
                    <option value="{{ $option['value'] }}" {{ $option['selected'] }}>
                        {{ $option['content'] }}
                    </option>
                @endforeach
                @foreach($params as $index => $param)
                    <option data-index="{{ $index }}"
                            value="{{ $param[$optionValue] }}" {{ $value == $param[$optionValue] ? 'selected' : '' }}>
                        {{ $param[$optionContent] ?? $param['nombre'] ?? $param['name'] }}
                        {{ $optionContentExtra != '' ? ' --- ' . data_get($param, $optionContentExtra) : '' }}
                    </option>
                @endforeach
                @foreach($optionsExtraEnd as $option)
                    <option value="{{ $option['value'] }}" {{ $option['selected'] }}>
                        {{ $option['content'] }}
                    </option>
                @endforeach
            </select>
            @break
    @endswitch
    @error($name)
    <small class="text-danger">*{{ $message }}</small>
    @enderror
</div>
