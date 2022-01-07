<div>
    <form wire:submit.prevent="save" method="post">
        @csrf

        <div class="row">
            <div class="form-group col-sm-12">
                <label for="diagnosis">Diagnosis</label>
                <textarea wire:model="diagnosis" id="diagnosis" rows="3" class="bs-form-control"></textarea>
            </div>

            <div class="form-group col-sm-12">
                <label for="treatment">Treatment</label>
                <textarea wire:model="treatment" id="treatment" rows="3" class="bs-form-control"></textarea>
            </div>

            <div class="form-group col-sm-12">
                <label for="comments">Comments</label>
                <textarea wire:model="comments" id="comments" rows="3" class="bs-form-control"></textarea>
            </div>

            <div class="form-group col-sm-12">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>

    </form>
</div>
