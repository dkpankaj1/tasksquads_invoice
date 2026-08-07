<tr>
    {{-- item id/name --}}
    <td>
        <input type="hidden" name="items[product_id][]" value="{{ $item->id }}">
        <input type="hidden" name="items[name][]" value="{{ $item->name }}">
        <span class="form-control form-control-sm bg-mute mb-1">{{ text_uppercase($item->name) }}</span>
        <textarea name="items[description][]" class="form-control form-control-sm" rows="2" placeholder="Item description.."></textarea>
    </td>

    {{-- item Hsn Code --}}
    <td>
        <input type="hidden" name="items[hsn_code][]" value="{{ $item->hsn_code }}">
        <span class="form-control form-control-sm bg-mute">{{ text_uppercase($item->hsn_code) }}</span>
    </td>

    {{-- item unit --}}
    <td>
        <input type="hidden" name="items[unit_id][]" value="{{ $item->unit->id }}">
        <input type="hidden" name="items[unit][]" value="{{ $item->unit->short_name }}">
        <span class="form-control form-control-sm bg-mute">{{ $item->unit->short_name }} [{{ $item->unit->id }}]</span>
    </td>

    {{-- item quantity --}}
    <td>
        <input name="items[quantity][]" type="number" step="0.01" class="form-control form-control-sm"
            value="1" />
    </td>

    {{-- item rate --}}
    <td>
        <input name="items[rate][]" type="number" step="0.01" class="form-control form-control-sm"
            value="{{ $item->rate }}" />
    </td>

    {{-- item additional cost --}}
    <td>
        <input name="items[additional_cost][]" type="number" step="0.01" class="form-control form-control-sm"
            value="{{ $item->additional_cost }}" />
    </td>

    {{-- item total amount --}}
    <td>
        <input name="items[amount][]" type="number" step="0.01" class="form-control form-control-sm"
            value="{{ $item->amount + $item->additional_cost }}" />
    </td>

    <td>
        <button class="btn btn-danger btn-sm" onclick="removeItem(this)">
            <i class="mdi mdi-trash-can"></i>
        </button>
    </td>
</tr>
