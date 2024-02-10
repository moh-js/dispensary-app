<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cash Report</title>

    <style>
        * {
            font-size: 1px !important;
        }

        .table {
            width: 100% !important;
            margin-bottom: 1rem;
        }

        .table tbody tr td, .table thead tr th {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px !important;
            padding-bottom: 5px !important;
            margin: 0px !important;
        }

        tr, tr {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .container {
          padding: 5px 12px 5px 12px !important;
        }

        .line {
          margin-left: 10px !important;
          margin-right: 10px !important;
        }

        .table-bordered, .table-bordered thead tr th, .table-bordered tbody tr td {
            border: 1px solid;
            border-collapse: collapse;
        }

        @page {
            /* margin: 0px; */
            /* font-size: 10px !important; */
        }

    </style>

</head>
<body>

    <div class="text-left">
        <table class="">
            <tbody>
                <tr>
                    <td rowspan="3" width="30%">
                        <img src="{{ public_path('image/logo.jpg') }}" width="100" alt="logo">
                    </td>
                    <td><h3>Mbeya University of Science and Technology</h3></td>
                </tr>
                <tr>
                    <td>{{ getAppName() }}</td>
                </tr>
                <tr>
                    <td>{{ getAppAddress() }} | {{ getAppPhone() }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>
    <h2 class="text-center">{{ strtoupper($status) }}CASHBOOK REPORT FROM {{ $dates }}</h2>
    <table class="table table-bordered">
        <thead class="">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Date</th>
                <th class="">Service</th>
                <th class="text-center">Cash</th>
                <th class="text-center">NHIF</th>
                <th class="text-center">Exempted</th>
                <th>Price</th>
                <th>Requested By</th>
            </tr>
            </thead>
            <tbody>
                {{-- chunk counter --}}
                @php
                    $chunkLimiter = 0;
                @endphp

                @foreach ($orders as $key => $order)
                @foreach ($order->items as $childKey => $item)

                    {{-- increment chunk on every loop --}}
                    @php
                        ++$chunkLimiter;
                    @endphp

                    <tr>
                        @if (!$childKey)
                            <td class="text-center" rowspan="{{ $order->items->count() }}" scope="row">{{ ++$key }}</td>
                            <td class="text-center" rowspan="{{ $order->items->count() }}">
                                {{ $item->order->created_at->format('d M - H:i') }}
                            </td>
                        @endif
                        <td title="{{ $item->service->name??'' }}">{{ str_limit($item->service->name??'', 15) }}</td>
                        <td class="text-center">
                            @if ($item->payment_type == 'cash')
                                Cash
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->payment_type == 'nhif')
                                NHIF
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->payment_type == 'exempted')
                                Exempted
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->total_price) }} {{ getAppCurrency() }}</td>
                        <td>{{ $item->order->cashier->name??'' }}</td>

                        {{-- check chunk limiter --}}
                        @if ($chunkLimiter === 500)
                        @php
                            $chunkLimiter = 0;
                        @endphp
                        <html-separator/>
                        @endif
                    </tr>
                @endforeach
                @endforeach
                @if ($orders->count())
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
                @else
                    <tr>
                        <td colspan="8" class="text-center">No data found according to your search</td>
                    </tr>
                @endif
            </tbody>
    </table>
</body>
</html>
