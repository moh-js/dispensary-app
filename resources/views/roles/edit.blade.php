@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Edit {{ $role->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('roles.update', $role->name) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">

                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name')??$role->name }}" id="name" placeholder="Name">
                        @error('name')
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
