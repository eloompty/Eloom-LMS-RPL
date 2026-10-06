@extends('user::layouts.master')
@section('title', 'Admin | Company')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Company</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Company</li>
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
                            <img class="profile-user-img img-fluid img-circle" id="box-image" src="{{ asset($company->logo) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ $company->company_name }}</h3>

                        <p class="text-muted text-center">{{ $company->trading_name }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>RTO No</b> <a class="float-right">{{ $company->rto_no }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>CRICOS No</b> <a class="float-right">{{ $company->cricos_no }}</a>
                            </li>
                        </ul>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About Company</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ $company->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ $company->phone }}</p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                        <p class="text-muted">{{ fullAddress('company', $company->id) }}</p>
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
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Update Details</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form class="form-horizontal" id="company" method="POST" action="{{ route('admin.company.update', $company->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="company_name" class="col-sm-2 col-form-label">Company Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}" placeholder="Company Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="trading_name" class="col-sm-2 col-form-label">Trading Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="trading_name" name="trading_name" value="{{ $company->trading_name }}" placeholder="Trading Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="company_ceo" class="col-sm-2 col-form-label">Company C.E.O.</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="company_ceo" name="company_ceo" value="{{ $company->company_ceo }}" placeholder="Company C.E.O.">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="rto_no" class="col-sm-2 col-form-label">RTO No</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="rto_no" name="rto_no" value="{{ $company->rto_no }}" placeholder="RTO No">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="cricos_no" class="col-sm-2 col-form-label">CRICOS No</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="cricos_no" name="cricos_no" value="{{ $company->cricos_no }}" placeholder="CRICOS No">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $company->email }}" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="phone" name="phone" value="{{ $company->phone }}" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="building_number" class="col-sm-2 col-form-label">Building Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="building_number" name="building_number" value="{{ $company->address->building_number }}" placeholder="Building Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="flat_unit" class="col-sm-2 col-form-label">Flat/Unit</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="flat_unit" class="form-control" id="flat_unit" placeholder="Enter Flat/Unit" value="{{ $company->address->flat_unit }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="street_no" class="col-sm-2 col-form-label">Street No</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="street_no" class="form-control" id="street_no" placeholder="Enter Street No" value="{{ $company->address->street_no }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="street_address" class="col-sm-2 col-form-label">Street Address</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="street_address" name="street_address" value="{{ $company->address->street_address }}" placeholder="Street Address">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="p_o_box" class="col-sm-2 col-form-label">P.O.Box</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="p_o_box" class="form-control" id="p_o_box" placeholder="Enter P.O.Box" value="{{ $company->address->p_o_box }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="suburb" class="col-sm-2 col-form-label">Suburb</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="suburb" name="suburb" value="{{ $company->address->suburb }}" placeholder="Suburb">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="state" class="col-sm-2 col-form-label">State</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="state" id="state">
                                                <option value="">-- Select State --</option>
                                                @foreach(getStates() as $state)
                                                <option value="{{ $state->value }}" @if($state->value == $company->address->state) selected @endif>{{ $state->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="zip_code" class="col-sm-2 col-form-label">Postal Code</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ $company->address->zip_code }}" placeholder="Postal Code">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="zip_code" class="col-sm-2 col-form-label">Country</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="country_id">
                                                <option value="" selected disabled>-- Select Country --</option>
                                                @foreach ($countries as $key => $value)
                                                <option value="{{ $key }}" @if ($key==$company->address->country_id) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-sm-2 col-form-label">Image</label>
                                        <div class="col-sm-10">
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
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

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#company').validate({
            rules: {
                company_name: {
                    required: true,
                },
                trading_name: {
                    required: true,
                },
                rto_no: {
                    required: true
                },
                // cricos_no: {
                //     required: true
                // },
                email: {
                    required: true
                },
                phone: {
                    required: true,
                    minlength: 7
                },
                building_number: {
                    maxlength: 50
                },
                flat_unit: {
                    maxlength: 30
                },
                street_no: {
                    required: true,
                    maxlength: 15
                },
                street_address: {
                    required: true,
                    maxlength: 70
                },
                suburb: {
                    required: true,
                    maxlength: 50
                },
                state: {
                    required: true
                },
                zip_code: {
                    required: true,
                    digits: true,
                    maxlength: 4
                },
            },
            messages: {
                company_name: "Please enter company name",
                trading_name: "Please enter trading name",
                rto_no: "Please enter RTO No",
                // cricos_no: "Please enter CRICOS No",
                email: "Please enter Email",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                building_number: {
                    maxlength: "Building Name cannot be longer than 50 characters"
                },
                flat_unit: {
                    maxlength: "Flat unit cannot be longer than 30 characters"
                },
                street_no: {
                    required: "Please enter street number",
                    maxlength: "Street Address cannot be longer than 15 characters"
                },
                street_address: {
                    required: "Please enter street address",
                    maxlength: "Street Address cannot be longer than 70 characters"
                },
                suburb: {
                    required: "Please enter suburb",
                    maxlength: "Street Address cannot be longer than 50 characters"
                },
                state: "Please enter state",
                zip_code: {
                    required: "Please enter postal code",
                    digits: "Postal Code needs to be number only"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection