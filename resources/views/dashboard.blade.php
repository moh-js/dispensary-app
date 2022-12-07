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
                        <h2 class="m-b-0">{{ App\Models\User::getUsers()->role('doctor')->count() }}</h2>
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
                        <h2 class="m-b-0">{{ App\Models\User::getUsers()->role('examiner')->count() }}</h2>
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
                        <h2 class="m-b-0">{{ App\Models\User::getUsers()->count() - ((App\Models\User::getUsers()->role('examiner')->count()) + (App\Models\User::getUsers()->role('doctor')->count())) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-md-12">
        {{-- <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="graph col-lg-8">
                        <h5 class="d-inline">Average Patient Visit</h5>
                        <select style="cursor: pointer;" class="float-right" onchange="loadPatientVisitChart()" name="visit-duration" id="visit-duration">
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
        </div> --}}

        <div class="card">
            <div class="card-body">
                
                <div class="col-md-12">
                    <div class="month">      
                        <ul>
                          
                          <li>
                            {{ now()->format('F') }}<br>
                            <span style="font-size:18px">{{ now()->format('Y') }}</span>
                          </li>
                        </ul>
                      </div>
                      
                      <ul class="weekdays">
                        <li>Mo</li>
                        <li>Tu</li>
                        <li>We</li>
                        <li>Th</li>
                        <li>Fr</li>
                        <li>Sa</li>
                        <li>Su</li>
                      </ul>
                      
                      <ul class="days">
                        @for ($i = 1; $i < now()->daysInMonth; $i++)
                            @if ($i === now()->day)
                            <li><span class="active">{{ $i }}</span></li>
                            @else
                            <li>{{ $i }}</li>  
                            @endif
                        @endfor
                      </ul>
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
                <div class="latest-update-box">
                    @foreach ($activities as $activity)
                        @if ($activity->auditable_type == 'App\Models\Patient' && $activity->event == 'created')
                            <div class="row p-t-20">
                                <div class="col-auto text-end update-meta d-flex align-content-center" style="min-width: 70px !important;">
                                    <i class="feather icon-user-plus bg-c-blue update-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6>Registration</h6>
                                    </a>
                                    <p class="text-muted m-b-0">New patient registered by name <i><strong>{{ title_case(App\Models\Patient::find($activity->auditable_id)->name) }}</strong></i></p>
                                    <p class="m-b-0">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                        @elseif($activity->auditable_type == 'App\Models\Encounter' && $activity->event == 'created')
                            <div class="row p-t-20">
                                <div class="col-auto text-end update-meta d-flex align-content-center" style="min-width: 70px !important;">
                                    <i class="feather icon-file-text bg-c-yellow update-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6>Encounter</h6>
                                    </a>
                                    <p class="text-muted m-b-0">New patient encounter created for patient <i><strong>{{ title_case(App\Models\Patient::find($activity->new_values['patient_id']??0)->name??'') }}</strong></i></p>
                                    <p class="m-b-0">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                        @endif
                    @endforeach
                </div>
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
    <link rel="stylesheet" href="{{ asset('assets/css/calender.css') }}">

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
    <script src="{{ asset('assets/js/plugins/echarts.min.js') }}"></script>
    <!-- Chartisan -->
    <script src="{{ asset('assets/js/plugins/chartisan_echarts.js') }}"></script>

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
                smooth: true,
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

                    if (response.datasets[0].values.filter(Number).length) {
                        var maleNumber = (response.datasets[0].values.filter(Number).reduce(reducer));
                    } else {
                        var maleNumber = 0;
                    }

                    if (response.datasets[1].values.filter(Number).length) {
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

        // $('#calendar').mobiscroll().datepicker({
        //     controls: ['calendar'],
        //     display: 'inline',
        //     themeVariant: 'light'
        // });

        $(function () {
            getPatientVisitNumber()
        });
    </script>
@endpush
