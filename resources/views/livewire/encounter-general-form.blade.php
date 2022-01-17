<div>
    <form wire:submit.prevent="saveData">

        <div class="row">
            <div class="form-group col-sm-12">
                <label for="diagnosis">Diagnosis</label>
                <textarea wire:model="diagnosis" id="diagnosis" rows="3" class="form-control @error('diagnosis') is-invalid @enderror"></textarea>
                @error('diagnosis')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <label for="treatment">Treatment</label>
                <textarea wire:model="treatment" id="treatment" rows="3" class="form-control @error('treatment') is-invalid @enderror"></textarea>
                @error('treatment')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <label for="comments">Comments</label>
                <textarea wire:model="comments" id="comments" rows="3" class="form-control @error('comments') is-invalid @enderror"></textarea>
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
</div>
