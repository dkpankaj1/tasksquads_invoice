{{-- Input Select ::Begin --}}
<select {{ $attributes->merge(['class' => 'form-select']) }} name="{{ $name }}" id="{{ $id ?? $name }}">
    <option value="">---select---</option>
    {{ $slot }}
</select>
{{-- Input Select ::Begin --}}

{{-- Input Error ::Begin --}}
@error($name)
    <div class="invalid-feedback d-block my-1 text-danger">✖ {{ $message }}</div>
@enderror
{{-- Input Error ::Begin --}}
