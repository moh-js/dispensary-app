@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        {{-- <h5>User List</h5>
        <div class="float-right">
                <a href="{{ route('users.add') }}" class="btn btn-primary btn-sm ml-3">Add User</a>
        </div> --}}
    </div>
    <div class="card-body table-bordered-style">
        <form action="{{ route('dispensing.search') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                      <select name="when" id="when" class="form-control">
                          <option value="{{ null }}" selected>Choose from when...</option>
                          <option value="today" {{ 'today' == $when? 'selected':'' }}>Daily</option>
                          <option value="week" {{ 'week' == $when? 'selected':'' }}>Weekly</option>
                          <option value="month" {{ 'month' == $when? 'selected':'' }}>Monthly</option>
                          {{-- <option value="six_month" {{ 'six_month' == $when? 'selected':'' }}>6 Months</option> --}}
                          {{-- <option value="year" {{ 'year' == $when? 'selected':'' }}>Year</option> --}}

                      </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="#" class="btn btn-success float-right">Advanced Search</a>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        @foreach ($items as $item)
                        <th class="vertical-header text-center"><span>{{ $item->name }}</span></th>
                        @endforeach
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    {{ $order->patient->patient_id }}
                                </td>
                                @foreach ($items as $item)
                                    <td class="text-center">
                                        {{ ($order->getOrderItemById($item->id)->quantity)??'' }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ $order->updated_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right"><strong>Total Quantity</strong></td>
                            @foreach ($items as $item)
                                @php
                                    $total_quantity = 0;
                                @endphp
                                @foreach ($orders as $key => $order)
                                @php
                                    $total_quantity = (($item->getOrderServiceByOrderID($order->id)->quantity)??0) + $total_quantity;
                                @endphp
                                @endforeach
                                <td class="text-center">{{ $total_quantity }}</td>
                            @endforeach
                        </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('css')
    <style>
        .vertical-header span {
            writing-mode: vertical-rl;
            transform: rotate(200deg);
            text-align: left;
            max-height: 450px;
        }
    </style>
@endpush
