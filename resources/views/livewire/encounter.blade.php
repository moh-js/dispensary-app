<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <center>
                    {{-- <img class="img-radius img-fluid wid-100" src="{{ asset('image/patient-male.png') }}" alt="User image"> --}}
                    <h5> {{ $patient->name }} </h5>
                    <h5> {{ $patient->dob->diff(now())->y }} Years Old</h5>
                    <p><strong>{{ title_case($patient->gender) }}</strong></p>
                    {{-- <span class="badge badge-pill badge-primary">Active</span> --}}
                    <span class="badge badge-pill badge-danger">Inactive</span>
                </center>
            </div>
            <div class="list-group">
                <button type="button" wire:click="changeFlag('general')" class="list-group-item list-group-item-action {{ $general_flag?'active':'' }}">
                    <i class="feather icon-grid"></i>
                    General
                </button>
                <button type="button" wire:click="changeFlag('lab')" class="list-group-item list-group-item-action {{ $lab_flag?'active':'' }}">
                    <i class="feather icon-filter"></i>
                    Lab Investigation
                </button>
                <button type="button" wire:click="changeFlag('signs')" class="list-group-item list-group-item-action {{ $signs_flag?'active':'' }}">
                    <i class="feather icon-activity"></i>
                    Vital Signs
                </button>
                <button type="button" wire:click="changeFlag('allergies')" class="list-group-item list-group-item-action {{ $allergies_flag?'active':'' }}">
                    <i class="feather icon-trending-down"></i>
                    Allergies
                </button>
                <button type="button" wire:click="changeFlag('medical')" class="list-group-item list-group-item-action {{ $medical_flag?'active':'' }}">
                    <i class="feather icon-clipboard"></i>
                    Medical History
                </button>
                <button type="button" wire:click="changeFlag('bill')" class="list-group-item list-group-item-action {{ $bill_flag?'active':'' }}">
                    <i class="feather icon-credit-card"></i>
                    Service Bill
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">

                {{-- General --}}
                <div x-data="{general_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($general_flag)">
                    <div class="row mb-5">
                        <span style="font-size: 20px;" class="col-6 col-md-2" title="Temperature">
                            <i class="feather icon-thermometer text-danger"></i>
                            34 <sup>o</sup>C
                        </span>

                        <span style="font-size: 20px;" class="col-6 col-md-2" title="Height">
                            <i class="feather icon-arrow-up text-primary"></i>
                            134 Cm
                        </span>

                        <span style="font-size: 20px;" class="col-6 col-md-2" title="Weight">
                            <i class="feather icon-underline text-primary"></i>
                            54 Kg
                        </span>

                        <span style="font-size: 20px;" class="col-6 col-md-2" title="BMI">
                            <i class="feather icon-link-2 text-primary"></i>
                            20 Kg/m<sup>2</sup>
                        </span>

                        <span style="font-size: 20px;" class="col-6 col-md-2" title="Diastolic BP">
                            <i class="feather icon-corner-right-down text-success"></i>
                            72 mmHg
                        </span>

                        <span style="font-size: 20px;" class="col-6 col-md-2" title="Systolic BP">
                            <i class="feather icon-corner-left-up text-success"></i>
                            112 mmHg
                        </span>
                    </div>

                    <hr>

                    @livewire('encounter-general-form', ['encounter' => $patient], key($patient->id))
                </div>
                {{-- End General --}}

                {{-- Lab --}}
                <div x-data="{lab_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($lab_flag)">
                    <form action="#" method="post" x-data="{form_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($form_flag)">
                        @csrf

                        <div class="row">
                            <div class="form-group col-sm-4">
                              <label for="name">Investigation Name</label>
                              <input type="text" name="name" id="name" class="form-control" placeholder="Name" aria-describedby="name">
                            </div>

                            <div class="form-group col-sm-8">
                                <label for="result">Result</label>
                                <textarea name="result" id="result" rows="1" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>

                    <button x-show="@js(!$form_flag)" wire:click="showForm" type="button" class="float-right btn btn-primary mb-1">Add</button>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Result</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- End Lab --}}

                {{-- Vital --}}
                <div x-data="{signs_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($signs_flag)">
                    <form action="" method="post">
                        @csrf

                        <div class="row">
                            <div class="form-group col-sm-4">
                              <label for="weight">Weight (Kg)</label>
                              <input type="text" name="weight" id="weight" class="form-control" placeholder="Weight" aria-describedby="weight">
                            </div>

                            <div class="form-group col-sm-4">
                              <label for="height">Height (Cm)</label>
                              <input type="text" name="height" id="height" class="form-control" placeholder="Height" aria-describedby="height">
                            </div>

                            <div class="form-group col-sm-4">
                              <label for="bmi">BMI</label>
                              <input type="text" name="bmi" id="bmi" disabled class="form-control" placeholder="BMI" aria-describedby="bmi">
                            </div>

                            <div class="form-group col-sm-4">
                              <label for="temperature">Temperature ( <sup>o</sup>C )</label>
                              <input type="text" name="temperature" id="temperature" class="form-control" aria-describedby="temperature">
                            </div>

                            <div class="form-group col-sm-4">
                              <label for="systolic">Systolic BP (mmHg)</label>
                              <input type="text" name="systolic" id="systolic" class="form-control" aria-describedby="systolic">
                            </div>

                            <div class="form-group col-sm-4">
                              <label for="diastolic">Diastolic BP (mmHg)</label>
                              <input type="text" name="diastolic" id="diastolic" class="form-control" aria-describedby="diastolic">
                            </div>

                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- End Vital --}}
            </div>
        </div>
    </div>
</div>
