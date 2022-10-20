@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Patient Visit Report</h5>
        </div>
        <div class="card-body table-bordered-style">
            <form action="{{ route('patient-visit.search') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" value="{{ old('name') ?? $name }}"
                                id="name" placeholder="Patient Name / ID">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="when" id="when" class="form-control">
                                <option value="{{ null }}" selected>Choose from when...</option>
                                <option value="today" {{ 'today' == $when ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ 'week' == $when ? 'selected' : '' }}>Week</option>
                                <option value="month" {{ 'month' == $when ? 'selected' : '' }}>Month</option>
                                {{-- <option value="six_month" {{ 'six_month' == $when? 'selected':'' }}>6 Months</option> --}}
                                {{-- <option value="year" {{ 'year' == $when? 'selected':'' }}>Year</option> --}}

                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('patient-visit.advance') }}"
                                class="btn btn-success float-right">Advanced</a>
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
                        @foreach ($encounters as $key => $encounter)
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
                                    @foreach ($encounter->investigations as $investigation)
                                        <span class="badge badge-primary">
                                            {{ $investigation->service->name }}:
                                            {{ $investigation->service->price }}TZS
                                        </span>
                                        <div class="clearfix"></div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($encounter->prescriptions as $prescription)
                                        <span class="badge badge-primary">
                                            {{ $prescription->service->name }}:
                                            {{ $prescription->service->price }}TZS
                                        </span>
                                        <div class="clearfix"></div>
                                    @endforeach
                                </td>
                                <td>{{ $encounter->created_at->format('d M y - H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="float-right">
                {{ $encounters->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
