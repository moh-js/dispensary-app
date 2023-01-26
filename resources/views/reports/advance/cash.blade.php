@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Advanced Cash Report</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('cash.advance.search') }}" method="post">

                @csrf

                <div class="row justify-content-center">
                    <div class="col-sm-6">

                        <div class="form-group">
                            <label for="category">Service Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="{{ null }}">Choose...</option>
                                @foreach (\App\Models\ServiceCategory::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="payment_type">Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-control">
                                <option value="all" selected>All</option>
                                <option value="nhif">NHIF</option>
                                <option value="cash">Cash</option>
                                <option value="exempted">Exempted</option>
                            </select>
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
                            <label for="status">Payment Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="completed" selected>Completed</option>
                                <option value="pending">Pending</option>
                            </select>
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
