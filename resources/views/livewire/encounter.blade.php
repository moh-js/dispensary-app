<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <center>
                    {{-- <img class="img-radius img-fluid wid-100" src="{{ asset('image/patient-male.png') }}" alt="User image"> --}}
                    <h5> {{ $encounter->patient->name }} </h5>
                    <h5> {{ $encounter->patient->dob->diff(now())->y }} Years Old</h5>
                    <p><strong>{{ title_case($encounter->patient->gender) }}</strong></p>

                    @if ($encounter->status)
                    <span class="badge badge-pill badge-danger">Inactive</span>
                    @else
                    <span class="badge badge-pill badge-primary">Active</span>
                    @endif
                </center>
            </div>
            <div class="list-group">
                @can('encounter-general-info-view')
                    <button type="button" wire:click="changeFlag('general')" class="list-group-item list-group-item-action {{ $general_flag?'active':'' }}">
                        <i class="feather icon-grid"></i>
                        General
                    </button>
                @endcan
                @can('investigation-view')
                    <button type="button" wire:click="changeFlag('lab')" class="list-group-item list-group-item-action {{ $lab_flag?'active':'' }}">
                        <i class="feather icon-filter"></i>
                        Lab Investigation
                    </button>
                @endcan
                @can('procedure-view')
                    <button type="button" wire:click="changeFlag('procedure')" class="list-group-item list-group-item-action {{ $procedure_flag?'active':'' }}">
                        <i class="fa fa-procedures"></i>
                        Procedure
                    </button>
                @endcan
                @can('vital-view')
                    <button type="button" wire:click="changeFlag('signs')" class="list-group-item list-group-item-action {{ $signs_flag?'active':'' }}">
                        <i class="feather icon-activity"></i>
                        Vital Signs
                    </button>
                @endcan
                @can('prescription-view')
                    <button type="button" wire:click="changeFlag('prescription')" class="list-group-item list-group-item-action {{ $prescription_flag?'active':'' }}">
                        <i class="feather icon-plus-square"></i>
                        Prescription
                    </button>
                @endcan
                @can('medical-view')
                    <button type="button" wire:click="changeFlag('medical')" class="list-group-item list-group-item-action {{ $medical_flag?'active':'' }}">
                        <i class="feather icon-clipboard"></i>
                        Medical History
                    </button>
                @endcan
                @can('bill-view')
                    <button type="button" wire:click="changeFlag('bill')" class="list-group-item list-group-item-action {{ $bill_flag?'active':'' }}">
                        <i class="feather icon-credit-card"></i>
                        Service Bill
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-{{ session('message')['type'] }} alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ session('message')['text'] }}
                    </div>
                @endif

                <center class="text-center">
                    <div wire:loading wire:target="changeFlag">
                        <div class="spinner-border text-primary" {{-- style="width: 4rem; height: 4rem;" --}} role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </center>

                <div wire:loading.remove wire:target="changeFlag">
                    @can('encounter-general-info-view')
                    {{-- General --}}
                    <div x-data="{general_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($general_flag)">
                        <div class="row mb-5">
                            <span style="font-size: 15px;" class="col-6 col-md-2" title="Temperature">
                                <i class="feather icon-thermometer text-danger"></i>
                                {{ $encounter->vital->temperature??"-" }} <sup>o</sup>C
                            </span>

                            <span style="font-size: 15px;" class="col-6 col-md-2" title="Height">
                                <i class="feather icon-arrow-up text-primary"></i>
                                {{ $encounter->vital->height??"-" }} Cm
                            </span>

                            <span style="font-size: 15px;" class="col-6 col-md-2" title="Weight">
                                <i class="feather icon-underline text-primary"></i>
                                {{ $encounter->vital->weight??"-" }} Kg
                            </span>

                            <span style="font-size: 15px;" class="col-6 col-md-2" title="BMI">
                                <i class="feather icon-link-2 text-primary"></i>
                                {{ $encounter->vital->bmi??"-" }} Kg/m<sup>2</sup>
                            </span>

                            <span style="font-size: 15px;" class="col-6 col-md-2" title="Diastolic BP">
                                <i class="feather icon-corner-right-down text-success"></i>
                                {{ $encounter->vital->diastolic??"-" }} mmHg
                            </span>

                            <span style="font-size: 15px;" class="col-6 col-md-2" title="Systolic BP">
                                <i class="feather icon-corner-left-up text-success"></i>
                                {{ $encounter->vital->systolic??"-" }} mmHg
                            </span>
                        </div>

                        <hr>

                        @livewire('encounter-general-form', ['encounter' => $encounter])
                    </div>
                    {{-- End General --}}
                    @endcan

                    @can('investigation-view')
                    {{-- Lab --}}
                    <div x-data="{lab_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($lab_flag)">
                        @livewire('encounter-lab-form', ['investigation' => $investigation, 'encounter' => $encounter])

                        <div class="table-responsive" id="lab-list">
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
                                    @foreach ($encounter->investigations as $key => $investigation)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $investigation->service->name }}</td>
                                            <td>{{ $investigation->result }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" wire:click="$emit('editInvestigation', {{ $investigation->id }})" title="Edit" class="text-primary mr-3"><i class="feather icon-edit" style="font-size: 16px"></i></a>

                                                <a href="javascript:void(0)" onclick="removeInvestigation({{ $investigation }})" title="Remove" class="text-danger"><i class="feather icon-trash" style="font-size: 16px"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- End Lab --}}
                    @endcan


                    @can('procedure-view')
                    {{-- Procedure --}}
                    <div x-data="{procedure_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($procedure_flag)">
                        @livewire('encounter-procedure-form', ['procedure' => $procedure, 'encounter' => $encounter])

                        <div class="table-responsive" id="lab-list">
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
                                    @foreach ($encounter->procedures as $key => $procedure)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $procedure->service->name }}</td>
                                            <td>{{ $procedure->result }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" wire:click="$emit('editProcedure', {{ $procedure->id }})" title="Edit" class="text-primary mr-3"><i class="feather icon-edit" style="font-size: 16px"></i></a>

                                                <a href="javascript:void(0)" onclick="removeProcedure({{ $procedure }})" title="Remove" class="text-danger"><i class="feather icon-trash" style="font-size: 16px"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- End Procedure --}}
                    @endcan

                    @can('vital-view')
                    {{-- Vital --}}
                    <div x-data="{signs_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($signs_flag)">
                        @livewire('encounter-vitals-form', ['encounter' => $encounter])
                    </div>
                    {{-- End Vital --}}
                    @endcan


                    @can('prescription-view')
                    {{-- Prescription --}}
                    <div x-data="{prescription_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($prescription_flag)">
                        @livewire('encounter-prescription-form', ['investigation' => $investigation, 'encounter' => $encounter])

                        <div class="table-responsive" id="lab-list">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encounter->prescriptions as $key => $prescription)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $prescription->service->name }}</td>
                                            <td>{{ $prescription->quantity }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" wire:click="$emit('editPrescription', {{ $prescription->id }})" title="Edit" class="text-primary mr-3"><i class="feather icon-edit" style="font-size: 16px"></i></a>

                                                <a href="javascript:void(0)" onclick="removePrescription({{ $prescription }})" title="Remove" class="text-danger"><i class="feather icon-trash" style="font-size: 16px"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    {{-- End Prescription --}}
                    @endcan


                    @can('bill-view')
                    {{-- Service Bill --}}
                    <div x-data="{bill_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($bill_flag)">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service</th>
                                        <th>Quantity</th>
                                        <th>Payment Type</th>
                                        <th>Price</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encounter->patient->getLastPendingOrder()->items??[] as $key => $item)
                                        @livewire('bill-table-row', ['item' => $item, 'key' => ++$key], key($item->id))
                                    @endforeach

                                    @if (!count($encounter->patient->getLastPendingOrder()->items??[]))
                                        <tr>
                                            <td colspan="5" class="text-center"><span class="text-dark">No service bill found</span></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if ($encounter->patient->getLastPendingOrder())
                            {{-- <hr> --}}
                            {{-- <h4>Complete Order</h4> --}}
                            <form action="{{ route('bill.complete', $encounter->patient->getLastPendingOrder()->invoice_id??null) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group float-right">
                                            <button class="btn btn-primary" type="submit">
                                                Complete
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        @endif
                    </div>
                    {{-- End Service Bill --}}
                    @endcan
                </div>

            </div>
        </div>
    </div>

    @push('js')
    <script>
        function removeInvestigation (investigation) {
            if (confirm('Remove '+ investigation.service.name)) {
                Livewire.emit('removeInvestigation', investigation.id);
            }
        }

        function removePrescription (prescription) {
            if (confirm('Remove '+ prescription.service.name)) {
                Livewire.emit('removePrescription', prescription.id);
            }
        }

        function removeProcedure (procedure) {
            if (confirm('Remove '+ procedure.service.name)) {
                Livewire.emit('removeProcedure', procedure.id);
            }
        }

        function removeBillService(item) {
            if (confirm('Are you sure, Remove bill service ?')) {
                $('#'+item.id).submit()
            }
        }
    </script>
    @endpush
</div>
