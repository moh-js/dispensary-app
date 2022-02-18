@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Cash Report</h5></div>
    <div class="card-body table-bordered-style">
        <form action="{{ route('cash.search') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                      <input type="text"
                        class="form-control" name="name" value="{{ old('name')??$name }}" id="name" placeholder="Service Name">
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
            <table class="table table-bordered table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
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
                        @foreach ($orders as $key => $order)
                        @foreach ($order->items as $childKey => $item)
                            <tr>
                                @if (!$childKey)
                                    <td class="text-center" rowspan="{{ $order->items->count() }}" scope="row">{{ ++$key }}</td>
                                    <td class="text-center" rowspan="{{ $order->items->count() }}">{{ $item->order->created_at->format('d M y - H:i') }}</td>
                                @endif
                                <td>{{ $item->service->name??'' }}</td>
                                <td>
                                    @if ($item->payment_type == 'cash')
                                        @include('extras.tick-icon')
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_type == 'nhif')
                                        @include('extras.tick-icon')
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_type == 'exempted')
                                        @include('extras.tick-icon')
                                    @endif
                                </td>
                                <td>{{ $item->total_price }}</td>
                                <td>{{ $item->order->cashier->name??'' }}</td>
                            </tr>
                        @endforeach
                        @endforeach
                    </tbody>
            </table>
        </div>


        <div class="float-right">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection
