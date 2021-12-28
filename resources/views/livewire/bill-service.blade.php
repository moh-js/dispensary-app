<div>
    <form action="{{ route('bill.patient.add', [$patient->patient_id, ($order->invoice_id??null)]) }}" id="add" method="post">
        @csrf

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                  <label for="name"><strong>Service Category</strong></label>
                  <select wire:model="category_id" name="category" id="category" class="form-control">
                      <option value="{{ null }}">Choose...</option>
                      @foreach ($serviceCategory as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                  </select>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="form-group">
                  <label for="name"><strong>Service</strong></label>
                  <select name="service" wire:model="service_id" {{ $disabledServiceInput? 'disabled':'' }} id="service" class="form-control">
                      <option value="{{ null }}" selected>Choose...</option>
                      @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ strtoupper($service->proper_name) }}</option>
                      @endforeach
                  </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                  <label for="name"><strong>Unit</strong></label>
                  <select name="unit" id="unit" class="form-control">
                      <option value="{{ null }}" selected>Choose...</option>
                      @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                      @endforeach
                  </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                  <label for="quantity"><strong>Quantity</strong></label>
                  <input type="number"
                    class="form-control" id="quantity" wire:model="quantity" name="quantity" placeholder="Quantity">
                </div>
            </div>

            <div class="col-sm-4">
                <label for="price"><strong>Total Price</strong></label>
                <div class="input-group">
                    <input type="text" disabled class="form-control" value="{{ number_format($totalPrice) }}" id="price">
                    <div class="input-group-append">
                        <span class="input-group-text" id="inputGroupAppend">Tsh</span>
                    </div>
                </div>
            </div>



            <div class="col-sm-12">
                <div class="form-group">
                  <label for="name"><strong>Cashier</strong></label>
                  <input type="text" class="form-control" disabled value="{{ request()->user()->name }}" id="name" placeholder="Name">
                </div>
            </div>

        </div>
    </form>
</div>
