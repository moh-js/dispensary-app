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
                            <h5 class="modal-title" id="exampleModalCenterTitle">Modal Title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('bill.patient.add', [$patient->patient_id, $order->invoice_id??null]) }}" id="add" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                          <label for="name"><strong>Service Category</strong></label>
                                          <select name="category" id="category" class="form-control">
                                              <option value="{{ null }}">Choose...</option>
                                              @foreach (App\Models\ServiceCategory::all() as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                          <label for="name"><strong>Service</strong></label>
                                          <select name="service" id="service" class="form-control">
                                              <option value="{{ null }}">Choose...</option>
                                              @foreach (App\Models\Service::all() as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                          <label for="name"><strong>Unit</strong></label>
                                          <select name="unit" id="unit" class="form-control">
                                              <option value="{{ null }}">Choose...</option>
                                              @foreach (App\Models\Unit::all() as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                          <label for="quantity"><strong>Quantity</strong></label>
                                          <input type="number"
                                            class="form-control" id="quantity" name="quantity" placeholder="Quantity">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="price"><strong>Total Price</strong></label>
                                        <div class="input-group">
                                            <input type="text" disabled class="form-control" value="{{ number_format(1200) }}" id="price">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="inputGroupAppend">Tsh</span>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-sm-12">
                                        <div class="form-group">
                                          <label for="name"><strong>Cashier</strong></label>
                                          <input type="text" class="form-control" disabled value="{{ request()->user()->name }}" id="name" placeholder="Name">
                                        </div>
                                    </div>

                                </div>
                            </form>
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
