@extends('student::student.layouts.master')
@section('title', 'Student Profile')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset(Auth::guard('student')->user()->image) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ userName('Student', Auth::guard('student')->user()->id) }}</h3>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Country</b> <a class="float-right">{{ getIdentifierValue('COUNTRY IDENTIFIER', Auth::guard('student')->user()->country_id) }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Overseas Country</b> <a class="float-right">{{ Auth::guard('student')->user()->overseasCountry->name }}</a>
                            </li>
                        </ul>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ Auth::guard('student')->user()->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ Auth::guard('student')->user()->phone }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Mobile</strong>
                        <p class="text-muted">{{ Auth::guard('student')->user()->mobile }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Citizenship</strong>
                        <p class="text-muted">{{ Auth::guard('student')->user()->citizenship }}</p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                        <p class="text-muted">{{ fullAddress('Student', Auth::guard('student')->user()->id) }}</p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Overseas Location</strong>
                        <p class="text-muted">{{ Auth::guard('student')->user()->overseas_address }}</p>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <div class="form-group row">
                                    <label for="salutation" class="col-sm-2 col-form-label">Salutation</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="salutation" name="salutation" value="{{ Auth::guard('student')->user()->salutation }}" placeholder="Salutation" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ Auth::guard('student')->user()->first_name }}" placeholder="First Name" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="family_name" class="col-sm-2 col-form-label">Family Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="family_name" name="family_name" value="{{ Auth::guard('student')->user()->family_name }}" placeholder="Family Name" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::guard('student')->user()->email }}" placeholder="Email" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="phone" name="phone" value="{{ Auth::guard('student')->user()->phone }}" placeholder="Phone" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" name="mobile" value="{{ Auth::guard('student')->user()->mobile }}" placeholder="Mobile" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="country" class="col-sm-2 col-form-label">Country</label>
                                    <div class="col-sm-10">
                                        <input type="country" class="form-control" id="country" name="country" value="{{ getIdentifierValue('COUNTRY IDENTIFIER', Auth::guard('student')->user()->country_id) }}" placeholder="Country" disabled>
                                    </div>
                                </div>
                                @if (Auth::guard('student')->user()->address != NULL)
                                <div class="form-group row">
                                    <label for="building_number" class="col-sm-2 col-form-label">Building Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="building_number" name="building_number" value="{{ Auth::guard('student')->user()->address->building_number }}" placeholder="Building Name" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="street_address" class="col-sm-2 col-form-label">Street Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="street_address" name="street_address" value="{{ Auth::guard('student')->user()->address->street_address }}" placeholder="Street Address" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="suburb" class="col-sm-2 col-form-label">Suburb</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="suburb" name="suburb" value="{{ Auth::guard('student')->user()->address->suburb }}" placeholder="Suburb" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="state" class="col-sm-2 col-form-label">State</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="state" name="state" value="{{ Auth::guard('student')->user()->address->state }}" placeholder="State" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="zip_code" class="col-sm-2 col-form-label">Postal Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ Auth::guard('student')->user()->address->zip_code }}" placeholder="Postal Code" disabled>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label for="overseas_country" class="col-sm-2 col-form-label">Overseas Country</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="overseas_country_id" name="overseas_country_id" value="{{ Auth::guard('student')->user()->overseasCountry->name }}" placeholder="Oversees Country" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="overseas_address" class="col-sm-2 col-form-label">Overseas Addresss</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="overseas_address" name="overseas_address" value="{{ Auth::guard('student')->user()->overseas_address }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="emergency_contact_number" class="col-sm-2 col-form-label">Emergency Contact Number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" value="{{ Auth::guard('student')->user()->emergency_contact_number }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="emergency_contact_person" class="col-sm-2 col-form-label">Emergency Contact Person</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="emergency_contact_person" name="emergency_contact_person" value="{{ Auth::guard('student')->user()->emergency_contact_person }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="emergency_contact_relation" class="col-sm-2 col-form-label">Emergency Contact Relation</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" value="{{ Auth::guard('student')->user()->emergency_contact_relation }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection