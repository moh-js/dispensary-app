@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Add New {{ $category->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('items.store', $category->slug) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" placeholder="Name">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('short_name') is-invalid @enderror" name="short_name" value="{{ old('short_name') }}" id="short_name" placeholder="Short Name">
                        @error('short_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}" id="quantity" placeholder="Quantity">
                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('package_type') is-invalid @enderror" name="package_type" value="{{ old('package_type') }}" id="package_type" placeholder="Package Type">
                        @error('package_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">Tsh</span>
                        </div>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" id="price" placeholder="Price">
                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('manufacturer') is-invalid @enderror" name="manufacturer" value="{{ old('manufacturer') }}" id="manufacturer" placeholder="Manufacturer">
                        @error('manufacturer')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                @if ($category->id == 1 || $category->id == 2)

                <div class="col-sm-4">
                    <div class="form-group">
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
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">Tsh</span>
                        </div>
                        <input type="text" class="form-control @error('service_price') is-invalid @enderror" name="service_price" value="{{ old('service_price') }}" id="service_price" placeholder="Service Price">
                        @error('service_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @endif

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="expire_date"><strong>Expire Date</strong></label>
                      <input type="date"
                        class="form-control @error('expire_date') is-invalid @enderror" name="expire_date" value="{{ old('expire_date') }}" id="expire_date" placeholder="Expire Date">
                        @error('expire_date')
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
