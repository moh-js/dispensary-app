@extends('layouts.app')

@section('content')


<div class="row">

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center m-l-0">
                    <div class="col-auto">
                        <i class="icon fa fa-user-injured f-30 text-c-purple"></i>
                    </div>
                    <div class="col-auto">
                        <h6 class="text-muted m-b-10">Patients</h6>
                        <h2 class="m-b-0">{{ App\Models\Patient::query()->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center m-l-0">
                    <div class="col-auto">
                        <i class="icon fa fa-stethoscope f-30 text-c-green"></i>
                    </div>
                    <div class="col-auto">
                        <h6 class="text-muted m-b-10">Doctors</h6>
                        <h2 class="m-b-0">{{ App\Models\User::role('doctor')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center m-l-0">
                    <div class="col-auto">
                        <i class="icon fa fa-user-friends f-30 text-c-red"></i>
                    </div>
                    <div class="col-auto">
                        <h6 class="text-muted m-b-10">Lab Investigators</h6>
                        <h2 class="m-b-0">{{ App\Models\User::role('examiner')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center m-l-0">
                    <div class="col-auto">
                        <i class="icon fa fa-user-tie f-30 text-c-blue"></i>
                    </div>
                    <div class="col-auto">
                        <h6 class="text-muted m-b-10">Other Staffs</h6>
                        <h2 class="m-b-0">{{ App\Models\User::query()->count() - ((App\Models\User::role('examiner')->count()) + (App\Models\User::role('doctor')->count())) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="graph col-lg-8">
                        <h5 class="d-inline">Average Patient Visit</h5>
                        <select class="float-right" onchange="loadPatientVisitChart()" name="visit-duration" id="visit-duration">
                            <option value="day">Day</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                        </select>

                        <div id="patient-visit" style="height: 300px;"></div>
                    </div>

                    <div class="col-lg-2 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <span class="male-patient-icon">
                                        <i class="fa fa-male text-primary"></i>
                                    </span>
                                    <p class="mt-4">Male</p>
                                    <h6 id="male-patient-number">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <span class="female-patient-icon">
                                        <i class="fa fa-female text-warning"></i>
                                    </span>
                                    <p class="mt-4">Female</p>
                                    <h6 id="female-patient-number">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="d-inline"></h5>
                <div class="col-md-7">
                    <input id="calendar" type="hidden" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-12">
        <div class="card latest-activity-card">
            <div class="card-header">
                <h5>Latest Activity</h5>
            </div>
            <div class="card-body">
                {{-- <div class="latest-update-box">
                    <div class="row p-t-20 p-b-30">
                        <div class="col-auto text-end update-meta">
                            <p class="text-muted m-b-0 d-inline-flex">just now</p>
                            <i class="feather icon-sunrise bg-c-blue update-icon"></i>
                        </div>
                        <div class="col">
                            <a href="#!">
                                <h6>John Deo</h6>
                            </a>
                            <p class="text-muted m-b-15">The trip was an amazing and a life changing experience!!</p>
                        </div>
                    </div>
                    <div class="row p-b-30">
                        <div class="col-auto text-end update-meta">
                            <p class="text-muted m-b-0 d-inline-flex">5 min ago</p>
                            <i class="feather icon-file-text bg-c-blue update-icon"></i>
                        </div>
                        <div class="col">
                            <a href="#!">
                                <h6>Administrator</h6>
                            </a>
                            <p class="text-muted m-b-0">Free courses for all our customers at A1 Conference Room - 9:00 am tomorrow!</p>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="text-end">
                    <a href="#!" class=" b-b-primary text-primary">View all Activity</a>
                </div> --}}
            </div>
        </div>
    </div>
</div>

@endsection


@push('css')
    <link href="{{ asset('css/mobiscroll.jquery.min.css') }}" rel="stylesheet" />

    <style>
        .male-patient-icon {
            background-color: rgb(83,109,254, .2);
        }
        .male-patient-icon,.female-patient-icon {
            padding: 15px 18px 8px 18px;
            border-radius: 20%;
        }
        .female-patient-icon {
            background-color: rgb(254,186,87, .2);

        }
        .male-patient-icon i,.female-patient-icon i {
            font-size: 25px;
        }
        .graph select {
            background-color: rgb(83,109,254, .2);
            border-radius: 5px;
            border: none;
            padding: 3px 5px;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('js/mobiscroll.jquery.min.js') }}"></script>
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>

    <script>
        var duration = $('#visit-duration').val();

        // initialize the chart and get the data
        const chart = new Chartisan({
          el: '#patient-visit',
          url: "@chart('patient_visit')" + "?duration=" + duration,
          hooks: new ChartisanHooks()
            .legend()
            .colors(['#4680FF', '#FFBA57'])
            .tooltip()
            .datasets([{
                type: 'line',
                fill: false,
                smooth: false,
                lineStyle: { width: 2 },
                symbolSize: 7,
                animationEasing: 'elasticOut'
                }, 'bar']),
        });

        function loadPatientVisitChart() {
            var duration = $('#visit-duration').val();
            getPatientVisitNumber(); // get the number of patient

            // update the chart
            chart.update({
                url: "@chart('patient_visit')" + "?duration=" + duration,
            })
        }

        function getPatientVisitNumber() {
            var duration = $('#visit-duration').val();

            // spinner loader to be shown when fetching the values
            var spinnerMale = '<div class="spinner-border text-primary spinner-border-sm" role="status"> <span class="sr-only">Loading...</span> </div>';
            var spinnerFemale = '<div class="spinner-border text-warning spinner-border-sm" role="status"> <span class="sr-only">Loading...</span> </div>';

            // showing the spinner loader
            $('#male-patient-number').html(spinnerMale);
            $('#female-patient-number').html(spinnerFemale);

            // get the number of patients
            $.ajax({
                type: "get",
                url: "@chart('patient_visit')" + "?duration=" + duration,
                success: function (response) {
                    const reducer = (accumulator, curr) => accumulator + curr;

                    if (response.datasets[0].values.filter(Number)) {
                        var maleNumber = (response.datasets[0].values.filter(Number).reduce(reducer));
                    } else {
                        var maleNumber = 0;
                    }

                    if ((response.datasets[1].values.filter(Number)) {
                        var femaleNumber = (response.datasets[1].values.filter(Number).reduce(reducer));
                    } else {
                        var femaleNumber = 0;
                    }

                    // set the values to the front-end
                    $('#male-patient-number').html(maleNumber);
                    $('#female-patient-number').html(femaleNumber);
                }
            });
        }

        $('#calendar').mobiscroll().datepicker({
            controls: ['calendar'],
            display: 'inline',
            themeVariant: 'light'
        });

        $(function () {
            getPatientVisitNumber()
        });
    </script>
@endpush
