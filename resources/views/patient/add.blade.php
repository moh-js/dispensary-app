@extends('layouts.app')

@push('css')
    <style>
        label {
            font-weight: 900;
        }
    </style>
@endpush

@section('content')

<div class="card">
    <div class="card-header"><h5>Add New Patient</h5></div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="patient_id">Patient ID</label>
                      <input type="text"
                        class="form-control @error('patient_id') is-invalid @enderror" name="patient_id" value="{{ old('patient_id') }}" id="patient_id" placeholder="Patient ID">
                        @error('patient_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="patient_id">First Name</label>
                      <input type="text"
                        class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" id="first_name" placeholder="First Name">
                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="patient_id">Middle Name</label>
                      <input type="text"
                        class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}" id="middle_name" placeholder="Middle Name">
                        @error('middle_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="patient_id">Last Name</label>
                      <input type="text"
                        class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" id="last_name" placeholder="Last Name">
                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="patient_id">Date of Birth</label>
                      <input type="date"
                        class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" id="dob" placeholder="E-mail Address">
                        @error('dob')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                      <label for="patient_id">Phone #</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">+255</span>
                        </div>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" id="phone" placeholder="677239833">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="patient_id">Gender</label>
                      <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="{{ null }}">Choose...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="patient_id">Address</label>
                      <textarea name="address" id="address" cols="30" class="form-control @error('address') is-invalid @enderror"></textarea>
                        @error('address')
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
