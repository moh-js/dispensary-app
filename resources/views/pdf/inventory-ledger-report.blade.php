<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory Ledger Report</title>

    <style>
        * {
            font-size: 13px !important;
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
                        <img src="{{ public_path('image/must_logo.png') }}" width="100" alt="logo">
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
    <h2 class="text-center">INVENTORY LEDGER REPORT FROM {{ $dates }}</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th class="text-center">Operation</th>
                <th>From</th>
                <th>To</th>
                <th>Quantity</th>
                <th>Requested By</th>
                <th>When</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $chunkLimiter = 0;
                @endphp

                @foreach ($ledgers as $key => $ledger)
                    @php
                        ++$chunkLimiter;
                    @endphp
                    <tr>
                        <td scope="row">{{ ++$key }}</td>
                        <td>{{ $ledger->item->name??'' }}</td>
                        <td class="text-center">
                            @if ($ledger->type == 'sent')
                                <span class="badge badge-primary">{{ ($ledger->type??'') }}</span>
                            @else
                                <span class="badge badge-warning">{{ ($ledger->type.'d'??'') }}</span>
                            @endif
                        </td>
                        <td>{{ $ledger->from??'' }}</td>
                        <td>{{ $ledger->unit->name??'' }}</td>
                        <td>{{ $ledger->quantity??'' }}</td>
                        <td>{{ $ledger->user->name??'' }}</td>
                        <td>{{ $ledger->created_at->format('d M y - H:i') }}</td>

                        @if ($chunkLimiter === 500)
                        @php
                            $chunkLimiter = 0;
                        @endphp
                        <html-separator/>
                        @endif
                    </tr>
                @endforeach
            </tbody>
    </table>
</body>
</html>
