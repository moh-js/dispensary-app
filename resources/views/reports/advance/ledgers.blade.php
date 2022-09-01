@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Advanced Inventory Ledger Report</h5></div>
    <div class="card-body">
        <form action="{{ route('inventory-ledger.advance.search') }}" method="post">
            @csrf

            <div class="row justify-content-center">
                <div class="col-sm-6">

                    <div class="form-group">
                      <label for="service_name">Service Name</label>
                      <input type="text" name="service_name" id="service_name" class="form-control @error('service_name') is-invalid @enderror" placeholder="Enter service name" value="{{ old('service_name', 'All') }}">
                      @error('service_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="unit">Unit</label>
                      <select name="unit" id="unit" class="form-control">
                            <option value="all" selected>All</option>
                            @foreach ($units as $unit)
                                <option {{ old('unit') == $unit->slug? 'selected':'' }} value="{{ $unit->slug }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="datetimes">Date Range</label>
                        <input id="datetimes" type="text" name="datetimes" class="form-control">
                        @error('datetimes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="submit" value="View">
                        <input type="submit" class="btn btn-danger" name="submit" value="PDF">
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('css')
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/daterangepicker.css') }}">
@endpush

@push('js')
    <!-- datepicker js -->
    <script src="{{ asset('assets/js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/daterangepicker.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/ac-datepicker.js') }}"></script> --}}

    <script>
        $(function() {
          $('#datetimes').daterangepicker({
            // timePicker: true,
            drops: 'up',
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            //   format: 'DD/MM/Y' //HH:mm
            }
          });
        });
    </script>
@endpush
