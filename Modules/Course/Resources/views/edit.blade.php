@extends('user::layouts.master')
@section('title', 'Admin | Edit Course')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Course</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($registered==1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="editcourse" @if ($registered==1) action="{{ route('admin.course.update', $course->id) }}" @else action="{{ route('admin.unregistered.update', $course->id) }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Course</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="course_name">Course Name</label> <span class="required">*</span>
                                <input type="text" name="course_name" class="form-control" id="course_name" placeholder="Enter Course Name" value="{{ $course->course_name }}">
                            </div>
                            <div class="form-group">
                                <label for="details">Course Details</label> <span class="required">*</span>
                                <textarea class="form-control" rows="3" placeholder="Enter Course Details" name="details">{{ $course->details }}</textarea>
                            </div>
                            @if ($registered == 1)
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="course_code">Course Code</label> <span class="required">*</span>
                                        <input type="text" name="course_code" class="form-control" id="course_code" placeholder="Enter Course Code" value="{{ $course->course_code }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="cricos_code">CRICOS Code</label>
                                        <input type="text" name="cricos_code" class="form-control" id="cricos_code" placeholder="Enter CRICOS Code" value="{{ $course->cricos_code }}">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="entry_requirements">Entry Requirements</label>
                                <textarea class="form-control" rows="3" placeholder="Enter Entry Requirements" name="entry_requirements">{{ $course->entry_requirements }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="pathways">Pathways</label>
                                <textarea class="form-control" rows="3" placeholder="Enter Pathways" name="pathways">{{ $course->pathways }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name" value="{{ $course->reference_name }}">
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="duration">Duration</label> <span class="required">*</span>
                                        <input type="number" name="duration" class="form-control" id="duration" placeholder="Enter Duration" value="{{ $course->duration }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="study_period">Study Period</label> <span class="required">*</span>
                                        <input type="number" name="study_period" class="form-control" id="study_period" placeholder="Enter Study Period" value="{{ $course->study_period }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="study_break">Study Break</label> <span class="required">*</span>
                                        <input type="number" name="study_break" class="form-control" id="study_break" placeholder="Enter Study Break" value="{{ $course->study_break }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="hours">Hours</label> <span class="required">*</span>
                                        <input type="number" name="hours" class="form-control" id="hours" placeholder="Enter Hours" value="{{ $course->hours }}">
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fee">Fee</label> <span class="required">*</span>
                                        <input type="number" name="fee" class="form-control" id="fee" placeholder="Enter Fee" step="0.01" value="{{ $course->fee }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="onshore_fee">Onshore Fee</label> <span class="required">*</span>
                                            <input type="number" name="onshore_fee" class="form-control" id="onshore_fee" placeholder="Enter Onshore Fee" step="0.01" value="{{ $course->onshore_fee }}">
                                        </div>
                                </div>
                            </div> -->

                            <!-- <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fee">Fee</label> <span class="required">*</span>
                                        <input type="number" name="fee" class="form-control" id="fee" placeholder="Enter Fee" step="0.01" value="{{ $course->fee }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="initial_fee">Initial Payment</label>
                                        <input class="initial_fee" name="initial_fee" type="checkbox" @if ($course->fee_initial > 0) checked value="1" @else value="0" @endif onchange="initialFee()" />
                                    </div>
                                </div>
                                <div class="col-md-3 offshore_initial">
                                    <div class="form-group">
                                        <label for="fee">Intial Fee</label>
                                        <input type="number" name="fee_initial" class="form-control" id="fee_initial" placeholder="Enter Initial Offshore Fee" step="0.01" value="{{ $course->fee_initial }}">
                                    </div>
                                </div>
                                <div class="col-md-3 offshore_initial">
                                    <div class="form-group">
                                        <label for="fee">Remaining Installment</label>
                                        <input type="number" name="fee_installment" class="form-control" id="fee_installment" placeholder="Enter Number of Installment" value="{{ $course->fee_installment }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="onshore_fee">Onshore Fee</label> <span class="required">*</span>
                                        <input type="number" name="onshore_fee" class="form-control" id="onshore_fee" placeholder="Enter Onshore Fee" step="0.01" value="{{ $course->onshore_fee }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="initial_onshore_fee">Initial Onshore Payment</label>
                                        <input class="initial_onshore_fee" name="initial_onshore_fee" type="checkbox" @if ($course->onshore_initial > 0) checked value="1" @else value="0" @endif onchange="initialOnshoreFee()" />
                                    </div>
                                </div>
                                <div class="col-md-3 onshore_initial">
                                    <div class="form-group">
                                        <label for="fee">Intial Fee</label>
                                        <input type="number" name="onshore_initial" class="form-control" id="onshore_initial" placeholder="Enter Initial Onshore Fee" step="0.01" value="{{ $course->onshore_initial }}">
                                    </div>
                                </div>
                                <div class="col-md-3 onshore_initial">
                                    <div class="form-group">
                                        <label for="fee">Remaining Installment</label>
                                        <input type="number" name="onshore_installment" class="form-control" id="onshore_installment" placeholder="Enter Number of Installment" value="{{ $course->onshore_installment }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="enrollment_fee">Enrollment Fee</label> <span class="required">*</span>
                                        <input type="number" name="enrollment_fee" class="form-control" id="enrollment_fee" placeholder="Enter Enrollment Fee" step="0.01" value="{{ $course->enrollment_fee }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="material_fee">Material Fee</label> <span class="required">*</span>
                                        <input type="number" name="material_fee" class="form-control" id="material_fee" placeholder="Enter Material Fee" step="0.01" value="{{ $course->material_fee }}">
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="total_units">Total Units</label> <span class="required">*</span>
                                <input type="number" name="total_units" class="form-control" id="total_units" placeholder="Enter Total Units" value="{{ $course->total_units }}">
                            </div>
                            <div class="form-group">
                                <label for="delivery_mode">Delivery Mode</label> <span class="required">*</span>
                                <select name="delivery_mode" class="form-control" id="delivery_mode">
                                    <option value="" selected disabled>-- Select Delivery Mode --</option>
                                    @foreach(getIdentifiers('DELIVERY MODE IDENTIFIER') as $delivery_mode_identifier)
                                    <option value="{{ $delivery_mode_identifier->value }}" @if($delivery_mode_identifier->value == $course->delivery_mode) selected @endif>{{ $delivery_mode_identifier->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="predominant_delivery_mode">Predominant Delivery Mode</label> <span class="required">*</span>
                                <select name="predominant_delivery_mode" class="form-control" id="predominant_delivery_mode">
                                    <option value="" selected disabled>-- Select Predominant Delivery Mode --</option>
                                    @foreach(getIdentifiers('PREDOMINANT DELIVERY MODE') as $predominant_delivery_mode)
                                    <option value="{{ $predominant_delivery_mode->value }}" @if($predominant_delivery_mode->value == $course->predominant_delivery_mode) selected @endif>{{ $predominant_delivery_mode->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="status">Course Delivery Site</label> <span class="required">*</span>
                                <select class="form-control" name="company_delivery_site_id">
                                    <option value="">-- Select Course Delivery Site --</option>
                                    @foreach($delivery_sites as $key => $value)
                                    <option value="{{ $value->id }}" @if ($course_delivery_site==NULL) @elseif ($value->id==$course->deliverSite->company_delivery_site_id) selected @endif> {{ $value->site_name }}</option>
                                    @endforeach
                                </select>
                            </div> -->

                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($course->status == '1')selected @endif value="1">Active</option>
                                    <option @if($course->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->

                <!--/.col (right) -->
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
        $('#editcourse').validate({
            rules: {
                course_name: {
                    required: true,
                },
                details: {
                    required: true,
                },
                course_code: {
                    required: true,
                },
                // cricos_code: {
                //     required: true,
                // },
                // entry_requirements: {
                //     required: true,
                // },
                // pathways: {
                //     required: true,
                // },
                reference_name: {
                    required: true,
                },
                delivery_mode: {
                    required: true,
                },
                predominant_delivery_mode: {
                    required: true,
                },
                duration: {
                    required: true,
                },
                study_period: {
                    required: true,
                },
                study_break: {
                    required: true,
                },
                hours: {
                    required: true,
                    digits: true,
                    maxlength: 4
                },
                // fee: {
                //     required: true,
                // },
                // onshore_fee: {
                //     required: true,
                // },
                // enrollment_fee: {
                //     required: true,
                // },
                // material_fee: {
                //     required: true,
                // },
                total_units: {
                    required: true,
                },
                company_delivery_site_id: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                course_name: "Please enter course name",
                details: "Please enter course details",
                course_code: "Please enter course code",
                // cricos_code: "Please enter CRICOS code",
                // entry_requirements: "Please enter Entry Requirements",
                // pathways: "Please enter Pathways",
                reference_name: "Please enter Reference Name",
                delivery_mode: "Please choose Delivery Mode",
                predominant_delivery_mode: "Please choose Predominant Delivery Mode",
                duration: "Please enter duration",
                study_period: "Please enter study period",
                study_break: "Please enter study break",
                hours: {
                    required: "Please enter hours",
                    maxlength: "Hours cannot be more than 4 digits"
                },
                // fee: "Please enter fee",
                // onshore_fee: "Please enter onshore fee",
                // enrollment_fee: "Please enter enrollment fee",
                // material_fee: "Please enter material fee",
                total_units: "Please enter total units",
                company_delivery_site_id: "Please choose one delivery site",
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

    var intialVal = $(".initial_fee").val();
    if (intialVal == 0) {
        $(".offshore_initial").hide();
    } else {
        $(".offshore_initial").show();
    }

    function initialFee() {
        if ($('.initial_fee').is(":checked")) {
            $(".offshore_initial").show();
        } else {
            $(".offshore_initial").hide();
        }
    }

    var intialVal = $(".initial_onshore_fee").val();
    if (intialVal == 0) {
        $(".onshore_initial").hide();
    } else {
        $(".onshore_iniital").show();
    }

    function initialOnshoreFee() {
        if ($('.initial_onshore_fee').is(":checked")) {
            $(".onshore_initial").show();
        } else {
            $(".onshore_initial").hide();
        }
    }
</script>
@endsection