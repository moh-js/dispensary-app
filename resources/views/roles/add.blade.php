@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Add New Role</h5></div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="post">
            @csrf
            <div class="row">

                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" placeholder="Name">
                        @error('name')
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
