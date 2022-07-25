<form wire:submit.prevent="saveData">
    <div class="row">
        <div class="form-group col-sm-4">
            <label for="weight">Weight (Kg)</label>
            <input type="text" wire:model="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="Weight" aria-describedby="weight">
            @error('weight')
                <div class="text-danger">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="height">Height (Cm)</label>
            <input type="text" wire:model="height" id="height" class="form-control @error('height') is-invalid @enderror" placeholder="Height" aria-describedby="height">
            @error('height')
                <div class="text-danger">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="bmi">BMI</label>
            <input type="text" wire:model="bmi" id="bmi" disabled class="form-control @error('bmi') is-invalid @enderror" placeholder="BMI" aria-describedby="bmi">
            @error('bmi')
                <div class="text-danger">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="temperature">Temperature ( <sup>o</sup>C )</label>
            <input type="text" wire:model="temperature" id="temperature" class="form-control @error('temperature') is-invalid @enderror" aria-describedby="temperature">
            @error('temperature')
                <div class="text-danger">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="systolic">Systolic BP (mmHg)</label>
            <input type="text" wire:model="systolic" id="systolic" class="form-control @error('systolic') is-invalid @enderror" aria-describedby="systolic">
            @error('systolic')
                <div class="text-danger">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="diastolic">Diastolic BP (mmHg)</label>
            <input type="text" wire:model="diastolic" id="diastolic" class="form-control @error('diastolic') is-invalid @enderror" aria-describedby="diastolic">
            @error('diastolic')
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
