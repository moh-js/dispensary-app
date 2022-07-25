<tr>
    <td>{{ $key }}</td>
    <td>{{ $item->service->name }}</td>
    <td>{{ $item->quantity }}</td>
    <td>
        @php
            $sessionName = $item->order->patient->patient_id.'session'.$item->id;
        @endphp
        @if (!$item->payment_type || session()->has($sessionName))
        <select wire:model="payment" id="payment_type{{ $item->id }}" class="d-inline bs-form-control form-control-sm">
            <option value="{{ null }}">Choose...</option>
            <option value="cash">Cash</option>
            <option value="nhif">NHIF</option>
            <option value="exempted">Exempted</option>
        </select>
        <div class="d-inline">
            <div wire:loading wire:target="payment" class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        @else
        {{ title_case($item->payment_type) }}
        @endif
    </td>
    <td>{{ $item->total_price }}</td>
    <td class="text-center">
        <a href="javascript:void(0)" onclick="removeBillService({{ $item }})" class="text-danger"><i class="feather icon-trash"></i></a>

        <form action="{{ route('bill.service.delete', $item->id) }}" id="{{ $item->id }}" method="post">
            @csrf
            @method('DELETE')
        </form>
    </td>
</tr>
