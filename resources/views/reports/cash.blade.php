@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Cash Report</h5></div>
    <div class="card-body table-bordered-style">
        <form action="{{ route('inventory-ledger.search') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                      <input type="text"
                        class="form-control" name="name" value="{{ old('name')??$name }}" id="name" placeholder="Amoxicillin">
                    </div>
                </div>
                {{-- <div class="col-sm-3">
                    <div class="form-group">
                      <select name="unit" id="unit" class="form-control">
                          <option value="{{ null }}" selected>Choose Unit...</option>
                          @foreach (App\Models\Unit::all() as $u)
                              <option value="{{ $u->slug }}" {{ ($u->slug) == $unit? 'selected':'' }}>{{ $u->name }}</option>
                          @endforeach
                      </select>
                    </div>
                </div> --}}

                <div class="col-sm-3">
                    <div class="form-group">
                      <select name="when" id="when" class="form-control">
                          <option value="{{ null }}" selected>Choose from when...</option>
                          <option value="today" {{ 'today' == $when? 'selected':'' }}>Today</option>
                          <option value="week" {{ 'week' == $when? 'selected':'' }}>Week</option>
                          <option value="month" {{ 'month' == $when? 'selected':'' }}>Month</option>
                          <option value="six_month" {{ 'six_month' == $when? 'selected':'' }}>6 Months</option>
                          <option value="year" {{ 'year' == $when? 'selected':'' }}>Year</option>

                      </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="#" class="btn btn-success float-right">Export</a>
                    </div>
                </div>
            </div>
        </form>

        <hr>

        <div class="table-responsive">
            <table class="table table-striped table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th class="">Service</th>
                        <th>Cash</th>
                        <th>NHIF</th>
                        <th>Exempted</th>
                        <th>Price</th>
                        <th>Requested By</th>
                        {{-- <th>When</th> --}}
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $key => $item)
                            <tr>
                                <td scope="row">{{ ++$key }}</td>
                                {{-- @if ($item->order->created_at) --}}

                                {{-- @endif --}}
                                <td>{{ $item->order->created_at->format('d M y - H:i') }}</td>
                                <td>{{ $item->service->name??'' }}</td>
                                <td>{{ $item->payment_type == 'cash'?'Cash':'' }}</td>
                                <td>{{ $item->payment_type == 'nhif'?'NHIF':'' }}</td>
                                <td>{{ $item->payment_type == 'exempted'?'Exempted':'' }}</td>
                                <td>{{ $item->total_price }}</td>
                                <td>{{ $item->order->cashier->name??'' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
        <div class="float-right">
            {{ $items->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection
