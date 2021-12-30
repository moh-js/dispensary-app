<div>
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="form-group d-inline">
              {{-- <label for="">Search Inventory</label> --}}
              <input type="text"
                class="form-control @error('name') is-invalid @enderror" id="query" placeholder="Search Inventory" wire:model="query">
                @error('query')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group col-sm-2">
            @if ($loading)
            <button class="btn btn-primary m-2 has-ripple" type="button" disabled="">
                <span class="spinner-border spinner-border-sm" role="status"></span>
                Loading...
            <span class="ripple ripple-animate" style="height: 122.719px; width: 122.719px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -32.1407px; left: -28.5001px;"></span></button>

            @else
            <button type="button" wire:click="search" class="btn btn-primary">Find</button>
            @endif

        </div>
    </div>

    @if ($this->loading)
    <div class="d-flex justify-content-center">
        <div class="spinner-border spinner-border-lg text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    @elseif ($itemsListSection)
        <div class="table-bordered-style mt-5 ">
            <div class="table-responsive">
                <table class="table table-bordered table-inverse table-sm">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            <tr>
                                <td scope="row">1</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->price }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="showForm({{ $item->id }})" style="border-radius: 50%;" class="btn btn-sm btn-outline-primary has-ripple"><i class="feather icon-sliders"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
        </div>

    @elseif($formSection)
    <hr>
    <form action="{{ route('items.issue') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                  <label for="name"><strong>Name</strong></label>
                  <input type="text"
                    class="form-control" disabled value="{{ $item->name }}" id="name" placeholder="Name">
                </div>
            </div>

            <input type="text" hidden name="item_id" value="{{ $item->id }}">

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="category"><strong>Inventory Category</strong></label>
                  <input type="text"
                    class="form-control" disabled value="{{ $item->inventoryCategory->name }}" id="inventory_category">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="category"><strong>Receive or Send</strong></label>
                    <select id="type" wire:model="type" class="form-control @error('type') is-invalid @enderror">
                        <option selected value="{{ null }}">Choose action</option>
                        <option {{ old('type') == 'receive'? 'selected':'' }} value="receive">Receiving</option>
                        <option {{ old('type') == 'sent'? 'selected':'' }} value="sent">Sending</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <input type="text" hidden name="type" value="{{ $type }}">

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="category"><strong>From</strong></label>
                    <input type="text"
                    class="form-control @error('from') is-invalid @enderror" name="from" value="{{ $from??old('from') }}" id="from" placeholder="Receive From" {{ $from? 'disabled':'' }}>
                    @error('from')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="to"><strong>To</strong></label>
                    <select name="to" id="to" class="form-control select2 @error('to') is-invalid @enderror">
                        <option selected value="{{ null }}">Choose...</option>
                        @foreach ($units as $unit)
                            <option {{ old('to') == $unit->id? 'selected':'' }} value="{{ $unit->id }}" {{ old('to') == $unit->id? 'selected':''}}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('to')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="category"><strong>Quantity</strong></label>
                    <input type="text"
                    class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}" id="quantity" placeholder="Number of {{ $item->package_type }}">
                    @error('quantity')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="category"><strong>Issued by</strong></label>
                    <input type="text"
                    class="form-control" disabled value="{{ request()->user()->name }}" id="issued_by" >
                    @error('issued_by')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="issued_date"><strong>Issued Date</strong></label>
                  <input type="datetime-local" max="{{ now()->format('Y-m-d\TH:i') }}"
                    class="form-control @error('issued_date') is-invalid @enderror" name="issued_date" value="{{old('issued_date')??now()->format('Y-m-d\TH:i')}}" id="issued_date">
                    @error('issued_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12 mt-2">
                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Issue</button>
                </div>
            </div>

        </div>
    </form>

    @endif

</div>
