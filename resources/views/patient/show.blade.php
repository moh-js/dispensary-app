@extends('layouts.app')

@section('content')
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
								</div>
							</div>
							<h5 class="mb-1">{{ $user->name }}</h5>
							<p class="mb-2 text-muted">{{ title_case(str_replace('-', ' ', $user->role)) }}</p>
						</div>
						<div class="col-md-8 mt-md-4 mb">
							<div class="row mb-5">
								<div class="col-md-4">
									<div class="clearfix"></div>
									<a href="javascript:void(0)" class="mb-1 text-muted d-flex align-items-end text-h-primary"><i class="feather icon-calendar mr-2 f-18"></i>{{ $user->age }} Years Old</a>
									<div class="clearfix"></div>
									<a href="#!" class="mb-1 text-muted d-flex align-items-end text-h-primary"><i class="feather icon-phone mr-2 f-18"></i>{{ $user->phone? '+255'.$user->phone:'' }}</a>
								</div>
								<div class="col-md-4">
                                    <div class="media">
										<i class="fa fa-{{ $user->gender }} ml-1 mr-2 mt-1 f-18"></i>
										<div class="media-body">
											<p>{{ title_case($user->gender) }}</p>
										</div>
									</div>
									<div class="media">
										<i class="feather icon-map-pin mr-2 mt-1 f-18"></i>
										<div class="media-body">
											<p>{{ $user->address }}</p>
										</div>
									</div>
								</div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#encounterModal" class="btn btn-primary btn-sm d-md-block mb-md-2"><i class="feather"></i> Encounter</a>


                                    <div id="encounterModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="encounterModal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="encounterModal">Encounter</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($encounter)
                                                        <div class="alert alert-danger fade show" role="alert">
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                            Previous active encounter found !
                                                        </div>
                                                    @endif
                                                    <form id="encounter-form" action="{{ route('encounter.create') }}" method="POST">
                                                        @csrf

                                                        <input type="text" hidden name="patient_id" value="{{ $user->id }}">

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="name"><strong>Chief</strong></label>
                                                                    <select name="cheif" id="cheif" class="form-control">
                                                                        <option value="{{ null }}">Choose Chief...</option>
                                                                        @foreach ($users as $u)
                                                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                                        @endforeach
                                                                  </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="purpose"><strong>Purpose</strong></label>
                                                                    <select name="purpose" id="purpose" class="form-control">
                                                                        <option value="{{ null }}">Choose Purpose...</option>
                                                                        @foreach ($services as $s)
                                                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                                        @endforeach
                                                                  </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="payment_type"><strong>Payment Mode</strong></label>
                                                                <select name="payment_type" id="payment_type" class="form-control">
                                                                    <option value="{{ null }}">Choose...</option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="nhif">NHIF</option>
                                                                    <option value="exempted">Exempted</option>
                                                                </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <button class="btn btn-primary" type="submit">
                                                                        Encounter
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('bill.patient.page', $user->patient_id) }}" class="btn btn-danger btn-sm d-md-block">Service Bill</a>
                                </div>
							</div>
							<ul class="nav nav-tabs profile-tabs nav-fill" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link text-reset has-ripple" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false"><i class="feather icon-home mr-2"></i>Encounters<span class="ripple ripple-animate" style="height: 165.031px; width: 165.031px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(55, 58, 60); opacity: 0.4; top: -58.508px; left: 11.8358px;"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-reset has-ripple" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="feather icon-user mr-2"></i>Profile<span class="ripple ripple-animate" style="height: 167.641px; width: 167.641px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(55, 58, 60); opacity: 0.4; top: -61.8205px; left: 19.492px;"></span></a>
								</li>
								{{-- <li class="nav-item">
									<a class="nav-link text-reset has-ripple" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><i class="feather icon-phone mr-2"></i>My Contacts<span class="ripple ripple-animate" style="height: 205.594px; width: 205.594px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(55, 58, 60); opacity: 0.4; top: -80.797px; left: -36.1251px;"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-reset has-ripple active" id="gallery-tab" data-toggle="tab" href="#gallery" role="tab" aria-controls="gallery" aria-selected="true"><i class="feather icon-image mr-2"></i>Gallery<span class="ripple ripple-animate" style="height: 171.047px; width: 171.047px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(55, 58, 60); opacity: 0.4; top: -65.5235px; left: -29.4454px;"></span></a>
								</li> --}}
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 ">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card latest-update-card">
                    <div class="card-header">
                        <h4></h4>
                        {{-- <h5 class="font-weight-normal"><a href="#!" class="text-h-primary text-reset"><b class="font-weight-bolder">Josephin Doe</b></a> posted on your timeline</h5> --}}
                        {{-- <p class="mb-0 text-muted">50 minutes ago</p> --}}
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a> </li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="#!"><img src="assets/images/profile/bg-1.jpg" alt="" class="img-fluid"></a>

                    <div class="card-body">
                        <div class="latest-update-box">
                            @foreach ($user->encounters->sortByDesc('id') as $encounter)
                                <div class="row justify-content-center p-t-30 p-b-30">
                                    <div class="col-auto text-right update-meta">
                                        <p class="text-muted m-b-0 d-inline-flex">{{ $encounter->updated_at->diffForHumans() }}</p>
                                        <i class="fa fa-stethoscope bg-primary update-icon"></i>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('encounter', $encounter->id) }}">
                                            <h6>{{ $encounter->name??$encounter->updated_at->format('dmYHi') }}</h6>
                                        </a>
                                        <p class="text-muted m-b-0">{{ $encounter->service->name }}</p>

                                        @if ($encounter->status)
                                        <span class="badge badge-pill badge-danger">Inactive</span>
                                        @else
                                        <span class="badge badge-pill badge-primary">Active</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            {{-- <a href="#!" class="b-b-primary text-primary">View all Encounters</a> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Personal details</h5>
                        <button type="button" class="btn btn-primary btn-sm rounded m-0 float-right" data-toggle="collapse" data-target=".pro-det-edit" aria-expanded="false" aria-controls="pro-det-edit-1 pro-det-edit-2">
                            <i class="feather icon-edit"></i>
                        </button>
                    </div>
                    <div class="card-body border-top pro-det-edit collapse show" id="pro-det-edit-1">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Full Name</label>
                                <div class="col-sm-9">
                                    {{ $user->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Gender</label>
                                <div class="col-sm-9">
                                    {{ title_case($user->gender) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Birth Date</label>
                                <div class="col-sm-9">
                                    {{ $user->date }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Age</label>
                                <div class="col-sm-9">
                                    {{ $user->age }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Location</label>
                                <div class="col-sm-9">
                                    <p class="mb-0 text-muted">{{ $user->address }}</p>
                                    {{-- <p class="mb-0 text-muted">4289 Calvin Street</p>
                                    <p class="mb-0 text-muted">Baltimore, near MD Tower Maryland,</p>
                                    <p class="mb-0 text-muted">Maryland (21201)</p> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body border-top pro-det-edit collapse " id="pro-det-edit-2">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Full Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Full Name" value="Lary Doe">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Gender</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="customRadioInline1">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadioInline2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Birth Date</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" value="1994-12-16">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Martail Status</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="exampleFormControlSelect1">
                                        <option>Select Marital Status</option>
                                        <option>Married</option>
                                        <option selected="">Unmarried</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Location</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control">4289 Calvin Street,  Baltimore, near MD Tower Maryland, Maryland (21201)</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Contact Information</h5>
                        <button type="button" class="btn btn-primary btn-sm rounded m-0 float-right" data-toggle="collapse" data-target=".pro-dont-edit" aria-expanded="false" aria-controls="pro-dont-edit-1 pro-dont-edit-2">
                            <i class="feather icon-edit"></i>
                        </button>
                    </div>
                    <div class="card-body border-top pro-dont-edit collapse show" id="pro-dont-edit-1">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Mobile Number</label>
                                <div class="col-sm-9">
                                    {{ $user->phone }}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body border-top pro-dont-edit collapse " id="pro-dont-edit-2">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Mobile Number</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Full Name" value="+1 9999-999-999">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Email Address</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Ema" value="Demo@domain.com">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            {{--     <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">other Information</h5>
                        <button type="button" class="btn btn-primary btn-sm rounded m-0 float-right" data-toggle="collapse" data-target=".pro-wrk-edit" aria-expanded="false" aria-controls="pro-wrk-edit-1 pro-wrk-edit-2">
                            <i class="feather icon-edit"></i>
                        </button>
                    </div>
                    <div class="card-body border-top pro-wrk-edit collapse show" id="pro-wrk-edit-1">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Occupation</label>
                                <div class="col-sm-9">
                                    Designer
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Skills</label>
                                <div class="col-sm-9">
                                    C#, Javascript, Scss
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Jobs</label>
                                <div class="col-sm-9">
                                    Phoenixcoded
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body border-top pro-wrk-edit collapse " id="pro-wrk-edit-2">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Occupation</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Full Name" value="Designer">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Email Address</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Ema" value="Demo@domain.com">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bolder">Jobs</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox form-check d-inline-block mr-2">
                                        <input type="checkbox" class="custom-control-input" id="pro-wrk-chk-1" checked="">
                                        <label class="custom-control-label" for="pro-wrk-chk-1">C#</label>
                                    </div>
                                    <div class="custom-control custom-checkbox form-check d-inline-block mr-2">
                                        <input type="checkbox" class="custom-control-input" id="pro-wrk-chk-2" checked="">
                                        <label class="custom-control-label" for="pro-wrk-chk-2">Javascript</label>
                                    </div>
                                    <div class="custom-control custom-checkbox form-check d-inline-block mr-2">
                                        <input type="checkbox" class="custom-control-input" id="pro-wrk-chk-3" checked="">
                                        <label class="custom-control-label" for="pro-wrk-chk-3">Scss</label>
                                    </div>
                                    <div class="custom-control custom-checkbox form-check d-inline-block mr-2">
                                        <input type="checkbox" class="custom-control-input" id="pro-wrk-chk-4">
                                        <label class="custom-control-label" for="pro-wrk-chk-4">Html</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>


           {{--  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card user-card user-card-1">
                            <div class="card-header border-0 p-2 pb-0">
                                <div class="cover-img-block">
                                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="assets/images/widget/slider5.jpg" alt="" class="img-fluid">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="assets/images/widget/slider6.jpg" alt="" class="img-fluid">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="assets/images/widget/slider7.jpg" alt="" class="img-fluid">
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></a>
                                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="user-about-block text-center">
                                    <div class="row align-items-end">
                                        <div class="col text-left pb-3"><a href="#!"><i class="icon feather icon-star text-muted f-20"></i></a></div>
                                        <div class="col"><img class="img-radius img-fluid wid-80" src="assets/images/user/avatar-4.jpg" alt="User image"></div>
                                        <div class="col text-right pb-3">
                                            <div class="dropdown">
                                                <a class="drp-icon dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-more-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-1 mt-3">Joseph William</h6>
                                    <p class="mb-3 text-muted">UI/UX Designer</p>
                                    <p class="mb-1">Lorem Ipsum is simply dummy text</p>
                                    <p class="mb-0">been the industry's standard</p>
                                </div>
                                <hr class="wid-80 b-wid-3 my-4">
                                <div class="row text-center">
                                    <div class="col">
                                        <h6 class="mb-1">37</h6>
                                        <p class="mb-0">Mails</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">2749</h6>
                                        <p class="mb-0">Followers</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">678</h6>
                                        <p class="mb-0">Following</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card user-card user-card-1">
                            <div class="card-header border-0 p-2 pb-0">
                                <div class="cover-img-block">
                                    <img src="assets/images/widget/slider6.jpg" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="user-about-block text-center">
                                    <div class="row align-items-end">
                                        <div class="col text-left pb-3"><a href="#!"><i class="icon feather icon-star-on text-c-yellow f-20"></i></a></div>
                                        <div class="col">
                                            <div class="position-relative d-inline-block">
                                                <img class="img-radius img-fluid wid-80" src="assets/images/user/avatar-5.jpg" alt="User image">
                                                <div class="certificated-badge">
                                                    <i class="fas fa-certificate text-c-blue bg-icon"></i>
                                                    <i class="fas fa-check front-icon text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col text-right pb-3">
                                            <div class="dropdown">
                                                <a class="drp-icon dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-more-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-1 mt-3">Suzen</h6>
                                    <p class="mb-3 text-muted">UI/UX Designer</p>
                                    <p class="mb-1">Lorem Ipsum is simply dummy text</p>
                                    <p class="mb-0">been the industry's standard</p>
                                </div>
                                <hr class="wid-80 b-wid-3 my-4">
                                <div class="row text-center">
                                    <div class="col">
                                        <h6 class="mb-1">37</h6>
                                        <p class="mb-0">Mails</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">2749</h6>
                                        <p class="mb-0">Followers</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">678</h6>
                                        <p class="mb-0">Following</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card user-card user-card-1">
                            <div class="card-header border-0 p-2 pb-0">
                                <div class="cover-img-block">
                                    <img src="assets/images/widget/slider7.jpg" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="user-about-block text-center">
                                    <div class="row align-items-end">
                                        <div class="col"></div>
                                        <div class="col">
                                            <div class="position-relative d-inline-block">
                                                <img class="img-radius img-fluid wid-80" src="assets/images/user/avatar-1.jpg" alt="User image">
                                            </div>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-1 mt-3">John Doe</h6>
                                    <p class="mb-3 text-muted">UI/UX Designer</p>
                                    <p class="mb-1">Lorem Ipsum is simply dummy text</p>
                                    <p class="mb-0">been the industry's standard</p>
                                </div>
                                <hr class="wid-80 b-wid-3 my-4">
                                <div class="row text-center">
                                    <div class="col">
                                        <h6 class="mb-1">37</h6>
                                        <p class="mb-0">Mails</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">2749</h6>
                                        <p class="mb-0">Followers</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">678</h6>
                                        <p class="mb-0">Following</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body hover-data text-white">
                                <div class="">
                                    <h4 class="text-white">Hire Me?</h4>
                                    <p class="mb-1">Lorem Ipsum is simply dummy text</p>
                                    <p class="mb-3">been the industry's standard</p>
                                    <button type="button" class="btn waves-effect waves-light btn-warning"><i class="feather icon-link"></i> Meating</button>
                                    <button type="button" class="btn waves-effect waves-light btn-danger"><i class="feather icon-download"></i> Resume</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card user-card user-card-2 shape-center">
                            <div class="card-header border-0 p-2 pb-0">
                                <div class="cover-img-block">
                                    <div id="carouselExampleControls-2" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="assets/images/widget/slider7.jpg" alt="" class="img-fluid">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="assets/images/widget/slider6.jpg" alt="" class="img-fluid">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="assets/images/widget/slider5.jpg" alt="" class="img-fluid">
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls-2" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></a>
                                        <a class="carousel-control-next" href="#carouselExampleControls-2" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="user-about-block text-center">
                                    <div class="row align-items-end">
                                        <div class="col text-left pb-3"><a href="#!"><i class="icon feather icon-star-on text-c-yellow f-20"></i></a></div>
                                        <div class="col">
                                            <div class="position-relative d-inline-block">
                                                <img class="img-radius img-fluid wid-80" src="assets/images/user/avatar-5.jpg" alt="User image">
                                                <div class="certificated-badge">
                                                    <i class="fas fa-certificate text-c-blue bg-icon"></i>
                                                    <i class="fas fa-check front-icon text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col text-right pb-3">
                                            <div class="dropdown">
                                                <a class="drp-icon dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-more-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-1 mt-3">Suzen</h6>
                                    <p class="mb-3 text-muted">UI/UX Designer</p>
                                    <p class="mb-1">Lorem Ipsum is simply dummy text</p>
                                    <p class="mb-0">been the industry's standard</p>
                                </div>
                                <hr class="wid-80 b-wid-3 my-4">
                                <div class="row text-center">
                                    <div class="col">
                                        <h6 class="mb-1">37</h6>
                                        <p class="mb-0">Mails</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">2749</h6>
                                        <p class="mb-0">Followers</p>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">678</h6>
                                        <p class="mb-0">Following</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            {{-- <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                <div class="row text-center">
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="assets/images/light-box/l1.jpg" data-lightbox="roadtrip"><img src="assets/images/light-box/sl1.jpg" class="img-fluid m-b-10 img-thumbnail bg-white" alt=""></a>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="assets/images/light-box/l2.jpg" data-lightbox="roadtrip"><img src="assets/images/light-box/sl2.jpg" class="img-fluid m-b-10 img-thumbnail bg-white" alt=""></a>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="assets/images/light-box/l3.jpg" data-lightbox="roadtrip"><img src="assets/images/light-box/sl3.jpg" class="img-fluid m-b-10 img-thumbnail bg-white" alt=""></a>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="assets/images/light-box/l4.jpg" data-lightbox="roadtrip"><img src="assets/images/light-box/sl4.jpg" class="img-fluid m-b-10 img-thumbnail bg-white" alt=""></a>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="assets/images/light-box/l5.jpg" data-lightbox="roadtrip"><img src="assets/images/light-box/sl5.jpg" class="img-fluid m-b-10 img-thumbnail bg-white" alt=""></a>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="assets/images/light-box/l6.jpg" data-lightbox="roadtrip"><img src="assets/images/light-box/sl6.jpg" class="img-fluid m-b-10 img-thumbnail bg-white" alt=""></a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>













   {{--  <div class="col-md-4 order-md-1">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Friends</h5>
                <span class="badge badge-light-primary float-right">100+</span>
            </div>
            <div class="card-body">
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-1.jpg" alt="user image" class="img-radius mb-2 wid-50" data-toggle="tooltip" title="" data-original-title="Joseph William"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-2.jpg" alt="user image" class="img-radius mb-2 wid-50" data-toggle="tooltip" title="" data-original-title="Sara Soudein"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-3.jpg" alt="user image" class="img-radius mb-2 wid-50" data-toggle="tooltip" title="" data-original-title="John Doe"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-4.jpg" alt="user image" class="img-radius mb-2 wid-50" data-toggle="tooltip" title="" data-original-title="Joseph William"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-5.jpg" alt="user image" class="img-radius wid-50" data-toggle="tooltip" title="" data-original-title="Sara Soudein"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-1.jpg" alt="user image" class="img-radius wid-50" data-toggle="tooltip" title="" data-original-title="Joseph William"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-2.jpg" alt="user image" class="img-radius wid-50" data-toggle="tooltip" title="" data-original-title="Sara Soudein"></a></li>
                    <li class="list-inline-item"><a href="#!"><img src="assets/images/user/avatar-3.jpg" alt="user image" class="img-radius wid-50" data-toggle="tooltip" title="" data-original-title="John Doe"></a></li>
                </ul>
            </div>
        </div>
        <div class="card new-cust-card">
            <div class="card-header">
                <h5>Message</h5>
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="feather icon-more-horizontal"></i>
                        </button>
                        <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                            <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                            <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                            <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="cust-scroll ps ps--active-y" style="height:415px;position:relative;">
                <div class="card-body p-b-0">
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-1.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>Alex Thompson</h6>
                            </a>
                            <p class="m-b-0">Cheers!</p>
                            <span class="status active"></span>
                        </div>
                    </div>
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-2.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>John Doue</h6>
                            </a>
                            <p class="m-b-0">stay hungry!</p>
                            <span class="status active"></span>
                        </div>
                    </div>
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-3.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>Alex Thompson</h6>
                            </a>
                            <p class="m-b-0">Cheers!</p>
                            <span class="status deactive">30 min ago</span>
                        </div>
                    </div>
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-4.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>John Doue</h6>
                            </a>
                            <p class="m-b-0">Cheers!</p>
                            <span class="status deactive">10 min ago</span>
                        </div>
                    </div>
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-5.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>Shirley Hoe</h6>
                            </a>
                            <p class="m-b-0">stay hungry!</p>
                            <span class="status active"></span>
                        </div>
                    </div>
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-1.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>John Doue</h6>
                            </a>
                            <p class="m-b-0">Cheers!</p>
                            <span class="status active"></span>
                        </div>
                    </div>
                    <div class="align-middle m-b-25">
                        <img src="assets/images/user/avatar-2.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>Jon Alex</h6>
                            </a>
                            <p class="m-b-0">stay hungry!</p>
                            <span class="status active"></span>
                        </div>
                    </div>
                    <div class="align-middle m-b-0">
                        <img src="assets/images/user/avatar-3.jpg" alt="user image" class="img-radius align-top m-r-15">
                        <div class="d-inline-block">
                            <a href="#!">
                                <h6>John Doue</h6>
                            </a>
                            <p class="m-b-0">Cheers!</p>
                            <span class="status deactive">10 min ago</span>
                        </div>
                    </div>
                </div>
            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 415px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 333px;"></div></div></div>
        </div>
    </div> --}}
</div>
@endsection
