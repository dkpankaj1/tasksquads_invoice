@props(['url', 'label', 'variation'=>'dark'])
<a href="{{ $url }}" class="btn btn-{{ $variation }} waves-effect waves-light px-4">
    {{ $label }}
</a>