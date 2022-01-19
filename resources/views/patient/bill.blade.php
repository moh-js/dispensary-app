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

        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Payment Type</th>
                        <th>Price</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items??[] as $key => $item)
                    @livewire('bill-table-row', ['item' => $item, 'key' => ++$key], key($item->id))
                    @endforeach
                    @if (!count($order->items??[]))
                        <tr>
                            <td colspan="5" class="text-center"><span class="text-dark">No service bill found</span></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mb-2">
            <div id="completeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="completeModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalTitle">Confirm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure?</p>
                            <form action="{{ route('bill.complete', $order->invoice_id??null) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group float-right">
                                            <button class="btn btn-primary" type="submit">
                                                Yes
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

        @if ($order && $order->status != 'completed')
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
