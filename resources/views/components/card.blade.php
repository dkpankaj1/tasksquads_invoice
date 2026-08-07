@props(['title','subTitle' => ""])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="card-header">
        <h5 class="card-title">{{ $title }}</h5>
         <p class="card-sub-title">{{$subTitle}}</p>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
