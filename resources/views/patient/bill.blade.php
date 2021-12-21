@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $patient->name }}</td>
                <td><strong>Patient ID:</strong></td>
                <td>{{ $patient->patient_id }}</td>
            </tr>
        </table>

        <hr>

        <div class="mb-2">
            <div id="exampleModalCenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Add Bill Service</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            @livewire('bill-service', ['patient' => $patient, 'order' => $order])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>
                            <button type="button" onclick="$('#add').submit()" class="btn  btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm float-right mb-2" data-toggle="modal" data-target="#exampleModalCenter"><i class="feather icon-plus-circle"></i></button>
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items??[] as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->service->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->total_price }}</td>
                    <td class="text-center">
                        <a href="javascript:void(0)" onclick="removeBillService({{ $item }})" class="text-danger"><i class="feather icon-trash"></i></a>

                        <form action="{{ route('bill.service.delete', $item->id) }}" id="{{ $item->id }}" method="post">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
                @if (!count($order->items??[]))
                    <tr>
                        <td colspan="5" class="text-center"><span class="text-dark">No service bill found</span></td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="mb-2">
            <div id="completeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="completeModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalTitle">Confirm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('bill.complete', $order->invoice_id??null) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                          <label for="name"><strong>Payment Mode</strong></label>
                                          <select name="payment_mode" id="payment_mode" class="form-control">
                                              <option value="{{ null }}">Choose...</option>
                                              <option value="cash">Cash</option>
                                              <option value="nhif">NHIF</option>
                                          </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">
                                                Complete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($order)
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#completeModal">Complete</button>
        @endif
    </div>
</div>

@endsection

@push('js')
    <script>
        function removeBillService(item) {
            if (confirm('Are you sure, Remove bill service ?')) {
                $('#'+item.id).submit()
            }
        }
    </script>
@endpush
