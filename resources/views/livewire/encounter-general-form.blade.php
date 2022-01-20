<div>
    <form wire:submit.prevent="saveData">

        <div class="row">
            <h5 class="col mb-4">1. History Taking</h5>

            <div class="form-group col-sm-12">
                <label for="chief_complains">Chief Complains</label>
                <textarea wire:model="chief_complains" id="chief_complains" rows="3" class="bs-form-control @error('chief_complains') is-invalid @enderror"></textarea>
                @error('chief_complains')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <label for="amplification">Amplification</label>
                <textarea wire:model="amplification" id="amplification" rows="3" class="bs-form-control @error('amplification') is-invalid @enderror"></textarea>
                @error('amplification')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <label for="review_of_systems">Review of Systems</label>
                <textarea wire:model="review_of_systems" id="review_of_systems" rows="3" class="bs-form-control @error('review_of_systems') is-invalid @enderror"></textarea>
                @error('review_of_systems')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <label for="past_medical">Past Medical</label>
                <textarea wire:model="past_medical" id="past_medical" rows="3" class="bs-form-control @error('past_medical') is-invalid @enderror"></textarea>
                @error('past_medical')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <label for="social_family_history">Social Family History</label>
                <textarea wire:model="social_family_history" id="social_family_history" rows="3" class="bs-form-control @error('social_family_history') is-invalid @enderror"></textarea>
                @error('social_family_history')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <h5 class="col mt-5 mb-2">2. Physcal Examination</h5>

            <div class="form-group col-sm-12">
                {{-- <label for="physical_examination">Treatment</label> --}}
                <textarea wire:model="physical_examination" id="physical_examination" rows="3" class="bs-form-control @error('physical_examination') is-invalid @enderror"></textarea>
                @error('physical_examination')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <h5 class="col mt-5 mb-2">3. Systemic / Local Examination</h5>

            <div class="form-group col-sm-12">
                {{-- <label for="physical_examination">Treatment</label> --}}
                <textarea wire:model="systemic_examination" id="systemic_examination" rows="3" class="bs-form-control @error('systemic_examination') is-invalid @enderror"></textarea>
                @error('systemic_examination')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <h5 class="col mt-5 mb-2">4. Provisional Diagnosis</h5>
            <div class="form-group col-sm-12">
                {{-- <label for="physical_examination">Treatment</label> --}}
                <textarea wire:model="provisional_diagnosis" id="provisional_diagnosis" rows="3" class="bs-form-control @error('provisional_diagnosis') is-invalid @enderror"></textarea>
                @error('provisional_diagnosis')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <h5 class="col mt-5 mb-2">5. Investigation</h5>
            <div class="form-group col-sm-12">
                {{-- <label for="physical_examination">Treatment</label> --}}
                <textarea wire:model="investigation" id="investigation" rows="3" class="bs-form-control @error('investigation') is-invalid @enderror"></textarea>
                @error('investigation')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <h5 class="col mt-5 mb-2">6. Treatment</h5>
            <div class="form-group col-sm-12">
                <textarea wire:model="treatment" id="treatment" rows="3" class="bs-form-control @error('treatment') is-invalid @enderror"></textarea>
                @error('treatment')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <h5 class="col mt-5 mb-2">7. Comments</h5>
            <div class="form-group col-sm-12">
                <textarea wire:model="comments" id="comments" rows="3" class="bs-form-control @error('comments') is-invalid @enderror"></textarea>
                @error('comments')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>

    </form>

    <form action="{{ route('encounter.close.open', $encounter->id) }}" method="post">
        @csrf

        @if ($encounter->status)
            <button type="submit" class="btn btn-warning float-right">Open Encounter</button>

        @else
            <button type="submit" class="btn btn-danger float-right">Close Encounter</button>
        @endif
    </form>
</div>
