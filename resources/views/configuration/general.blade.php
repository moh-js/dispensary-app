@extends('layouts.app')

@section('content')


<div class="card">
    <div class="card-header"><h5>General Setting</h5></div>
    <div class="card-body">
        <form action="{{ route('general.save') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="app_name">App Name</label>
                      <input type="text"
                        class="form-control @error('app_name') is-invalid @enderror" name="app_name" value="{{ old('app_name')??getAppName() }}" id="app_name" placeholder="App Name">
                        @error('app_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="app_short_name">App Short Name</label>
                      <input type="text"
                        class="form-control @error('app_short_name') is-invalid @enderror" name="app_short_name" value="{{ old('app_short_name')??getAppShortName() }}" id="app_short_name" placeholder="App Short Name">
                        @error('app_short_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="app_currency">App Currency</label>
                      <input type="text"
                        class="form-control @error('app_currency') is-invalid @enderror" name="app_currency" value="{{ old('app_currency')??getAppCurrency() }}" id="app_currency" placeholder="App Currency">
                        @error('app_currency')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-8">
                    <div class="form-group">
                      <label for="address">Organization Address</label>
                      <textarea name="address" id="address" rows="1" class="form-control @error('address') is-invalid @enderror">{{ getAppAddress() }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="phone">Organization Phone #</label>
                        <input type="text"
                          class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone')??getAppPhone() }}" id="phone" placeholder="App Currency">
                          @error('phone')
                              <div class="invalid-feedback">
                                  {{ $message }}
                              </div>
                          @enderror
                    </div>
                </div>

                <div class="col-sm-12 mt-2">
                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
