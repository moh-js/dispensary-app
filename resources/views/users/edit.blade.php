@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Add New User</h5></div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->username) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <select aria-placeholder="Choose Role" multiple name="role[]" id="role" class="form-control @error('role') is-invalid @enderror">
                            <option selected value="{{ null }}">Choose Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role')? (collect(old('role'))->contains($role->name)? 'selected':''):(collect($user->getRoleNames())->contains($role->name)? 'selected':'') }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name')??$user->first_name }}" id="first_name" placeholder="First Name">
                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name')??$user->middle_name }}" id="middle_name" placeholder="Middle Name">
                        @error('middle_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text"
                        class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name')??$user->last_name }}" id="last_name" placeholder="Last Name">
                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                      <input type="email"
                        class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email')??$user->email }}" id="email" placeholder="E-mail Address">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">+255</span>
                        </div>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone')??$user->phone }}" id="phone" placeholder="677239833">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select aria-placeholder="Choose Station" name="station_id" id="station" class="form-control @error('station_id') is-invalid @enderror">
                            <option selected value="{{ null }}">Choose Work Station</option>
                            @foreach ($stations as $station)
                                <option value="{{ $station->id }}" {{ old('station_id', $user->station_id) == $station->id ? 'selected':''}}>{{ title_case($station->name) }}</option>
                            @endforeach
                        </select>
                        @error('station_id')
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
