@extends('user::layouts.master')
@section('title', 'Admin | Add Company Delivery Site')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Company Delivery Sites</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.company.delivery.index') }}">Company Delivery Sites</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add <small>Company Delivery Site</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="adddeliverysite" action="{{ route('admin.company.delivery.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="site_name">Site Name</label> <span class="required">*</span>
                                        <input type="text" name="site_name" class="form-control" id="site_name" placeholder="Enter Site Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label> <span class="required">*</span>
                                        <input type="number" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="building_number">Building Name</label>
                                        <input type="text" name="building_number" class="form-control" id="building_number" placeholder="Enter Building Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="flat_unit">Flat/Unit</label>
                                        <input type="text" name="flat_unit" class="form-control" id="flat_unit" placeholder="Enter Flat/Unit">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="street_no">Street No</label> <span class="required">*</span>
                                        <input type="text" name="street_no" class="form-control" id="street_no" placeholder="Enter Street No">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="street_address">Street Address</label> <span class="required">*</span>
                                        <input type="text" name="street_address" class="form-control" id="street_address" placeholder="Enter Street Address">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="p_o_box">P.O.Box</label>
                                        <input type="text" name="p_o_box" class="form-control" id="p_o_box" placeholder="Enter P.O.Box">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="suburb">Suburb</label> <span class="required">*</span>
                                        <input type="text" name="suburb" class="form-control" id="suburb" placeholder="Enter Suburb">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="state">State</label> <span class="required">*</span>
                                        <select class="form-control" name="state" id="state">
                                            <option value="">-- Select State --</option>
                                            @foreach(getStates() as $state)
                                            <option value="{{ $state->value }}">{{ $state->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="zip_code">Postal Code</label> <span class="required">*</span>
                                        <input type="number" name="zip_code" class="form-control" id="zip_code" placeholder="Enter Postal Code">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="country_id">Country</label> <span class="required">*</span>
                                        <select class="form-control" name="country_id" id="country">
                                            <option value="">-- Select Country --</option>
                                            @foreach($countries as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
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
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("Form successful submitted!");
        //     }
        // });
        $('#adddeliverysite').validate({
            rules: {
                site_name: {
                    required: true,
                },
                phone: {
                    required: true,
                    minlength: 7
                },
                status: {
                    required: true
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
                site_name: "Please enter site name",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone numver must be at least 7 characters long"
                },
                status: "Please select one status",
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
</script>
@endsection