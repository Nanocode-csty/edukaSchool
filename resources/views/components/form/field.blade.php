@props(['name', 'label', 'type' => 'text', 'options' => []])

<div class="row mb-3 align-items-center">

    <label class="col-12 col-md-2 col-form-label">
        {{ $label }}
    </label>

    <div class="col-12 col-md-10">

        {{-- TEXT --}}
        @if ($type === 'text' || $type === 'email' || $type === 'number')

            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
                value="{{ old($name) }}" placeholder="{{ 'Ingrese' . ' ' . $label }}"
                {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>

            {{-- SELECT --}}
        @elseif($type === 'select')
            <select name="{{ $name }}" id="{{ $name }}"
                {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>

                <option value="">{{ 'Seleccione' . ' ' . $label }}</option>

                @foreach ($options as $value => $text)
                    <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>

                        {{ $text }}

                    </option>
                @endforeach

            </select>

            {{-- TEXTAREA --}}
        @elseif($type === 'textarea')
            <textarea name="{{ $name }}" id="{{ $name }}"
                {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>{{ old($name) }}</textarea>

            {{-- FILE --}}
        @elseif($type === 'file')
            <input type="file" name="{{ $name }}" id="{{ $name }}"
                {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>

        @endif

        {{-- ERRORES --}}
        @error($name)
            <div class="invalid-feedback d-block text-start">
                {{ $message }}
            </div>
        @enderror

        {{-- Slot para errores AJAX --}}
        {{ $slot }}

    </div>
</div>
