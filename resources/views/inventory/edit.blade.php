@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Edit {{ $item->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('items.update', $item->slug) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="category"><strong>Inventory Category</strong></label>
                      <select class="form-control" name="category" id="category">
                        <option value="{{ null }}" selected>Choose Category</option>
                        @foreach (App\Models\InventoryCategory::all() as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $item->inventory_category_id? 'selected':'' }}>{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="name"><strong>Name</strong></label>
                      <input type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name')??$item->name }}" id="name" placeholder="Name">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="quantity"><strong>Quantity</strong></label>
                        <input type="text"
                        class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity')??$item->quantity }}" id="quantity" placeholder="Quantity">
                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="uom"><strong>UoM</strong></label>
                        <input type="text"
                        class="form-control @error('uom') is-invalid @enderror" name="uom" value="{{ old('uom')??$item->uom }}" id="uom" placeholder="Unit of Measure">
                        @error('uom')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="service_name"><strong>Service Name</strong></label>
                        <input type="text"
                        class="form-control @error('service_name') is-invalid @enderror" name="service_name" value="{{ old('service_name')??$item->service->name }}" id="service_name" placeholder="Service Name">
                        @error('service_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="service_price"><strong>Service Price</strong></label>
                        <input type="text"
                        class="form-control @error('service_price') is-invalid @enderror" name="service_price" value="{{ old('service_price')??$item->service->price }}" id="service_price" placeholder="Per Unit">
                        @error('service_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12 mt-2">
                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
