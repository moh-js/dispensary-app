<div>
    <form wire:submit.prevent="saveData" x-data="{form_flag:0}" x-show.transition.opacity.in.duration.500ms="@json($form_flag)">
        @csrf

        <div class="row">
            <div class="form-group col-sm-8">
                <label for="service_id">Medicine Name</label>
                <select wire:model="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror">
                    <option value="{{ null }}" selected>Choose Medicine Name</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <label for="unit_id"><strong>Unit</strong></label>
                    <select wire:model="unit_id" id="unit_id" class="form-control @error('unit_id') is-invalid @enderror">
                        <option value="{{ null }}" selected>Choose...</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>

                    @error('unit_id')
                        <div class="text-danger">
                            <small>{{ $message }}</small>
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group col-sm-2">
                <label for="quantity">Quantity</label>
                <input type="number" wire:model="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror">
                @error('quantity')
                    <div class="text-danger">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
            </div>

            <div class="form-group col-sm-12">
                <button wire:click="clearForm" type="button" class="btn btn-danger mr-3">Cancel</button>
                @if ($prescription)
                <button type="submit" class="btn btn-primary">Update</button>
                @else
                <button type="submit" class="btn btn-primary">Save</button>
                @endif
            </div>
        </div>
    </form>

    @if (!$form_flag)
        <button wire:click="showForm" type="button" class="float-right btn btn-primary mb-1">Add</button>
    @endif
</div>
