<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient Visit Report</title>

    <style>
        * {
            font-size: 13px !important;
        }

        .table {
            width: 100% !important;
            margin-bottom: 1rem;
        }

        .table tbody tr td,
        .table thead tr th {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px !important;
            padding-bottom: 5px !important;
            margin: 0px !important;
        }

        tr,
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

        .table-bordered,
        .table-bordered thead tr th,
        .table-bordered tbody tr td {
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
                    <td>
                        <h3>Mbeya University of Science and Technology</h3>
                    </td>
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
    <h2 class="text-center">PATIENT VISIT REPORT FROM {{ $dates }}</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Phone</th>
                <th class="text-center">Vital Signs</th>
                <th>Medical Investigation</th>
                <th>Prescription</th>
                <th>When</th>
            </tr>
        </thead>
        <tbody>
            @php
                $chunkLimiter = 0;
            @endphp

            @foreach ($encounters as $key => $encounter)
                @php
                    ++$chunkLimiter;
                @endphp
                <tr>
                    <td scope="row">{{ ++$key }}</td>
                    <td>{{ $encounter->patient->name ?? '' }}</td>
                    <td>{{ $encounter->patient->age }}</td>
                    <td>{{ $encounter->patient->gender }}</td>
                    <td>{{ $encounter->patient->phone ?? '' }}</td>
                    <td>
                        <span class="badge badge-primary">
                            Weight:
                            {{ $encounter->vital->weight ?? 'nill' }}
                        </span>
                        <div class="clearfix"></div>
                        <span class="badge badge-primary">
                            Height:
                            {{ $encounter->vital->height ?? 'nill' }}
                        </span>
                        <div class="clearfix"></div>
                        <span class="badge badge-primary">
                            <Tarea>Temperature</Tarea>:
                            {{ $encounter->vital->temperature ?? 'nill' }}
                        </span>
                        <div class="clearfix"></div>
                        <span class="badge badge-primary">
                            BMI:
                            {{ $encounter->vital->bmi ?? 'nill' }}
                        </span>
                        <div class="clearfix"></div>
                        <span class="badge badge-primary">
                            Pressure Systolic:
                            {{ $encounter->vital->systolic ?? 'nill' }}
                        </span>
                        <div class="clearfix"></div>
                        <span class="badge badge-primary">
                            Pressure Diastolic:
                            {{ $encounter->vital->diastolic ?? 'nill' }}
                        </span>
                    </td>
                    <td>
                        @foreach ($encounter->investigations as $key => $investigation)
                            <span class="badge badge-primary">
                                {{ $investigation->service->name }}:
                                {{ $investigation->service->price }}TZS
                            </span>
                            @if ($encounter->investigations[$key+1] ?? false)
                                <hr>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($encounter->prescriptions as $key => $prescription)
                            <span class="badge badge-primary">
                                {{ $prescription->service->name }}:
                                {{ $prescription->service->price }}TZS,
                                {{ $prescription->quantity }} Units,
                                {{ $prescription->quantity * $prescription->service->price }}TZS Total
                            </span>
                            @if ($encounter->prescriptions[$key+1] ?? false)
                                <hr>
                            @endif
                            {{-- <div class="clearfix"></div> --}}
                        @endforeach
                    </td>
                    <td>{{ $encounter->created_at->format('d M y - H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
