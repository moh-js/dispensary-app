<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>

    <style>
        * {
            font-size: 13px !important;
        }

        .table {
            width: 100% !important;
            margin-bottom: 1rem;
        }

        td, th {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            margin: 0px !important;
        }

        tr {
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

        @page {
            margin: 0px;
            /* font-size: 10px !important; */
        }
    </style>

</head>
<body>
    <div class="container">
        <table class="table">
            <tbody>
                <tr>
                    <td rowspan="3">
                        <img src="{{ public_path('image/must_logo.png') }}" width="80" alt="logo">
                    </td>
                    <td>{{ getAppName() }}</td>
                </tr>
                <tr>
                    <td>{{ getAppAddress() }}</td>
                </tr>
                <tr>
                    <td>{{ getAppPhone() }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="line">

    <div class="container">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="text-left">Date</th>
                    <td class="text-left"><strong>: </strong>{{ $order->order_date->format('M d, Y H:i') }}</td>
                </tr>
                <tr>
                    <th class="text-left">Receipt #</th>
                    <td class="text-left"><strong>: </strong>{{ $order->receipt_id??'' }}</td>
                </tr>
                <tr>
                    <th class="text-left">Cashier</th>
                    <td class="text-left"><strong>: </strong>{{ $order->cashier->name??'' }}</td>
                </tr>
                <tr>
                    <th class="text-left">Customer</th>
                    <td class="text-left"><strong>: </strong>{{ $order->patient->name??'' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-left">&nbsp;&nbsp;0{{ $order->patient->phone??'' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="line">

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-left">Qty</th>
                    <th class="text-right">Payment</th>
                    {{-- <th class="text-right">Price</th> --}}
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td class="text-left">{{ $item->service->name }}</td>
                        <td class="text-left">{{ $item->quantity }}</td>
                        <td class="text-left">{{ $item->payment_type }}</td>
                        {{-- <td class="text-right">{{ number_format($item->sub_total) }}</td> --}}
                        <td class="text-right">{{ number_format($item->total_price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="container">
        <table class="table">
            <tbody>
                {{-- <tr>
                    <td>Total Quantity</td>
                    <td width="10%">1</td>
                    <td>Sub Totals</td>
                    <td>{{ number_format('15000') }} Tsh</td>
                </tr> --}}
                {{-- <tr>
                    <td></td>
                    <td></td>
                    <td>Discount</td>
                    <td class="text-right">0 Tsh</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Tax</td>
                    <td class="text-right">0 Tsh</td>
                </tr> --}}
                <tr>
                    <td width="25"></td>
                    <td width="25"></td>
                    <td class="text-right">Total</td>
                    <td class="text-right">{{ number_format($order->total_price) }} {{ getAppCurrency() }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="line">

    <div class="container">
        <h5 style="text-align: center">Thank You</h5>
    </div>
</body>
</html>
