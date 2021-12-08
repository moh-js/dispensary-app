<div>
    <a href="javascript:void(0)" wire:click="toogleSearchResult"><i class="feather icon-search"></i></a>

    <div class="search-bar {{ $searchResultContainer? 'd-block':'d-none' }}">
        <input type="text" wire:model="query" class="form-control border-0 shadow-none" placeholder="Enter patient ID or Name to Search">
        <button type="button" wire:click="toogleSearchResult" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        @if ($patients->count())
        <div class="text-dark" style="overflow: auto; max-height: 100vh;">
            @foreach ($patients as $key => $patient)
                <div class="d-flex {{ $key > 0? 'list':'' }} ml-4">
                    <a href="{{ route('patient.show', $patient->patient_id) }}" class="text-dark">
                        <div class="d-inline">
                            <span><strong>Patient ID: </strong></span>
                            <span class="ml-2">{{ $patient->patient_id }}</span>
                        </div>
                        <div class="d-inline ml-5">
                            <span><strong>Patient Name: </strong></span>
                            <span class="ml-2">{{ $patient->name }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        @else
        <div class="text-center">
            <a href="{{ route('patient.create') }}" class="text-primary">Add New Patient</a>
        </div>
        @endif
    </div>
</div>
