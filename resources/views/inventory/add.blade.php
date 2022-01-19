@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Add New {{ $category->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('items.store', $category->slug) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name"><strong>Name</strong></label>
                        <input type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" placeholder="Name">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="quantity"><strong>Quantity</strong></label>
                        <input type="text"
                        class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}" id="quantity" placeholder="Quantity">
                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="uom"><strong>UoM</strong></label>
                        <input type="text"
                        class="form-control @error('uom') is-invalid @enderror" name="uom" value="{{ old('uom') }}" id="uom" placeholder="Unit of Measure">
                        @error('uom')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="countable"><strong>Countable</strong></label>
                        <select name="countable" id="countable" class="form-control @error('countable') is-invalid @enderror">
                            <option value="{{ 0 }}" {{ old('countable') == 0? 'selected':'' }}>No</option>
                            <option value="{{ 1 }}" {{ old('countable') == 1? 'selected':'' }}>Yes</option>
                        </select>
                        @error('countable')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

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
