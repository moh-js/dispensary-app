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
                          {{-- <option value="six_month" {{ 'six_month' == $when? 'selected':'' }}>6 Months</option> --}}
                          {{-- <option value="year" {{ 'year' == $when? 'selected':'' }}>Year</option> --}}

                      </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="#{{-- {{ route('cash.advance') }} --}}" class="btn btn-success float-right">Advance</a>
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
                                    <td class="text-center" rowspan="{{ $order->items->count() }}">
                                        <a title="View Receipt" href="{{ route('bill.completed', $order->receipt_id) }}">
                                            {{ $item->order->created_at->format('d M y - H:i') }}
                                        </a>
                                    </td>
                                @endif
                                <td title="{{ $item->service->name??'' }}">{{ str_limit($item->service->name??'', 15) }}</td>
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
                                <td class="text-right">{{ number_format($item->total_price) }} {{ getAppCurrency() }}</td>
                                <td>{{ $item->order->cashier->name??'' }}</td>
                            </tr>
                        @endforeach
                        @endforeach
                        <tr style="font-weight: 900;">
                            <td colspan="3" class="text-right">Total</td>
                            <td class="text-right">
                                @php
                                    $totalCashPrice = 0;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                        $totalCashPrice += $order->items->where('payment_type', 'cash')->sum('total_price')
                                    @endphp
                                @endforeach
                                {{ number_format($totalCashPrice) }} {{ getAppCurrency() }}
                            </td>
                            <td class="text-right">
                                @php
                                    $totalCashPrice = 0;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                        $totalCashPrice += $order->items->where('payment_type', 'nhif')->sum('total_price')
                                    @endphp
                                @endforeach
                                {{ number_format($totalCashPrice) }} {{ getAppCurrency() }}
                            </td>
                            <td></td>
                            <td class="text-right">
                                @php
                                    $totalCashPrice = 0;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                        $totalCashPrice += $order->items->sum('total_price')
                                    @endphp
                                @endforeach
                                {{ number_format($totalCashPrice) }} {{ getAppCurrency() }}
                            </td>
                        </tr>
                    </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
