@extends('layouts.app')

@section('content')
@php
    $user = request()->user();
@endphp
<div class="card">
    <div class="card-body">
        <div class="user-profile user-card mb-4">
			<div class="card-body py-0">
				<div class="user-about-block m-0">
					<div class="row">
						<div class="col-md-4 text-center mt-3">
							<div class="change-profile text-center">
								<div class="dropdown w-auto d-inline-block">
									<a class="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<div class="profile-dp">
											<div class="position-relative d-inline-block">
												<img class="img-radius img-fluid wid-100" src="{{ asset('image/patient-male.png') }}" alt="User image">
											</div>
										</div>
									</a>
									{{-- <div class="dropdown-menu" style="">
										<a class="dropdown-item" href="#"><i class="feather icon-upload-cloud mr-2"></i>upload new</a>
										<a class="dropdown-item" href="#"><i class="feather icon-image mr-2"></i>from photos</a>
										<a class="dropdown-item" href="#"><i class="feather icon-shield mr-2"></i>Protact</a>
										<a class="dropdown-item" href="#"><i class="feather icon-trash-2 mr-2"></i>remove</a>
									</div> --}}
								</div>
							</div>
							<h5 class="mb-1">{{ $user->name }}</h5>
							<p class="mb-2 text-muted">{{ title_case(str_replace('-', ' ', $user->role)) }}</p>
						</div>
						<div class="col-md-8 mt-md-4">
							<div class="row">
								<div class="col-md-6">
									{{-- <a href="#!" class="mb-1 text-muted d-flex align-items-end text-h-primary"><i class="feather icon-globe mr-2 f-18"></i>www.phoenixcoded.net</a> --}}
									<div class="clearfix"></div>
									<a href="mailto:demo@domain.com" class="mb-1 text-muted d-flex align-items-end text-h-primary"><i class="feather icon-mail mr-2 f-18"></i>{{ $user->email }}</a>
									<div class="clearfix"></div>
									<a href="#!" class="mb-1 text-muted d-flex align-items-end text-h-primary"><i class="feather icon-phone mr-2 f-18"></i>{{ $user->phone? '+255'.$user->phone:'' }}</a>
								</div>
								<div class="col-md-6">
									<div class="media">
										<i class="feather icon-map-pin mr-2 mt-1 f-18"></i>
										{{-- <div class="media-body">
											<p class="mb-0 text-muted">4289 Calvin Street</p>
											<p class="mb-0 text-muted">Baltimore, near MD Tower Maryland,</p>
											<p class="mb-0 text-muted">Maryland (21201)</p>
										</div> --}}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Activities</h5></div>
            <div class="card-body">

            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Change Password</h5>
            </div>
            <div class="card-body">

                <div class="row justify-content-center">
                    <div class="col-sm-8">
                        <div class="" style="border-radius: 5px">
                            <form action="{{ route('change.password') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                  <label for="old_password"><strong>Old Password</strong></label>
                                  <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="" aria-describedby="password">

                                  @error('old_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                  @enderror
                                </div>

                                <div class="form-group">
                                  <label for="password"><strong>New Password</strong></label>
                                  <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="" aria-describedby="password">

                                  @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                  @enderror
                                </div>

                                <div class="form-group">
                                  <label for="password_confirmation"><strong>Confirm Password</strong></label>
                                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="" aria-describedby="password">
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
