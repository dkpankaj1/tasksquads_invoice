{{-- Input TextArea ::Begin --}}
<textarea {{ $attributes->merge(['class' => 'form-control']) }} id="{{ $id ?? $name }}"
    placeholder="{{ $placeholder ?? '' }}" name="{{ $name }}">{{ old($name, $value ?? '') }}
</textarea>
{{-- Input TextArea ::Begin --}}

{{-- Input Error ::Begin --}}
@error($name)
    <div class="invalid-feedback d-block my-1 text-danger">✖ {{ $message }}</div>
@enderror
{{-- Input Error ::Begin --}}
