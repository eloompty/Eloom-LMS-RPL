@extends('user::layouts.master')
@section('title', 'Admin | Add Trainer/Accessor')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Trainer/Accessor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Trainers/Accessors</a></li>
                    <li class="breadcrumb-item active">Add Trainer/Accessor</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addtrainer" action="{{ route('admin.trainer.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Trainer/Accessor</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <h3>Personal Information </h3>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="salutation">Salutation</label> <span class="required">*</span>
                                        <select name="salutation" class="form-control" id="salutation">
                                            <option value="" selected disabled>-- Select Salutation --</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Miss">Miss</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label> <span class="required">*</span>
                                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="family_name">Family Name</label> <span class="required">*</span>
                                        <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth</label> <span class="required">*</span>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="phone">Phone</label> <span class="required">*</span>
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label> <span class="required">*</span>
                                        <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="email">Email</label> <span class="required">*</span>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="password">Password</label> <span class="required">*</span>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="image">Image</label> <span class="required">*</span>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                                    </div>
                                </div>
                            </div>
                            </br>
                            <h3>Address Information</h3>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="building_number">Building Name</label>
                                        <input type="text" name="building_number" class="form-control" id="building_number" placeholder="Enter Building Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="flat_unit">Flat Unit</label>
                                        <input type="text" name="flat_unit" class="form-control" id="flat_unit" placeholder="Enter Flat Unit">
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
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
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
        $('#addtrainer').validate({
            rules: {
                salutation: {
                    required: true,
                },
                first_name: {
                    required: true,
                },
                family_name: {
                    required: true,
                },
                date_of_birth: {
                    required: true,
                },
                phone: {
                    required: true,
                    minlength: 7
                },
                mobile: {
                    required: true,
                    minlength: 7
                },
                email: {
                    required: true,
                },
                image: {
                    required: true,
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
                country_id: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                salutation: "Please choose one salutation",
                first_name: "Please enter first name",
                family_name: "Please enter family name",
                date_of_birth: "Please enter date of birth",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                mobile: {
                    required: "Please enter mobile number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                email: "Please enter email",
                image: "Please upload image",
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
                country_id: "Please enter country",
                status: "Please select one status",

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
                    .width(80)
                    .height(80);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection