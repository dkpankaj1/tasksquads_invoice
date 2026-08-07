@foreach ($items as $item)
    <li class="searchResultItem" onclick="addItem('{{ $item->id }}')">{{ $item->name }} [{{ $item->id }}]</li>
@endforeach
