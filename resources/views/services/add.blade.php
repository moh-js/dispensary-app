@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Add New Service</h5></div>
    <div class="card-body">
        <form action="{{ route('services.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="service_name"><strong>Service Name</strong></label>
                        <input type="text"
                        class="form-control @error('service_name') is-invalid @enderror" name="service_name" value="{{ old('service_name') }}" id="service_name" placeholder="Service Name">
                        @error('service_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="service_category_id"><strong>Service Category</strong></label>
                        <select name="service_category_id" id="service_category_id" class="form-control @error('service_category_id') is-invalid @enderror">
                            <option value="{{ null }}" selected>Select Category...</option>
                            @foreach (App\Models\ServiceCategory::all() as $category)
                                <option {{ $category->id == old('service_category_id')? 'selected':'' }} value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('service_category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="service_price"><strong>Service Price</strong></label>
                        <input type="text"
                        class="form-control @error('service_price') is-invalid @enderror" name="service_price" value="{{ old('service_price') }}" id="service_price" placeholder="Per Unit">
                        @error('service_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="items_id"><strong>Items Used</strong></label>
                    <select name="items_id[]" data-placeholder="Select used items" multiple id="items_id" class="form-control js-example-basic-hide-search @error('items_id') is-invalid @enderror">
                            {{-- <option value="{{ null }}" selected>Select used items...</option> --}}
                            @foreach (App\Models\Item::where('inventory_category_id', 2)->get() as $item)
                                <option {{ ((old('item_id')) == ($item->id)? 'selected':'') }} value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('items_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12 mt-2">
                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2.css') }}">
@endpush


@push('js')
    <script src="{{ asset('assets/js/plugins/select2.full.min.js') }}"></script>
@endpush
