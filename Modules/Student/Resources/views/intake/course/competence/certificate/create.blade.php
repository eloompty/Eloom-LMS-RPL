@extends('user::layouts.master')
@section('title', 'Admin | Add Competence Certificate')

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
                <h1>Add Certificate</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeCourseCompetence->studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.competence.index', $studentIntakeCourseCompetence->student_intake_course_id) }}">Competence</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.competence.index', $studentIntakeCourseCompetence->id) }}">Certificates</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addCertificate" action="{{ route('admin.student.intake.course.competence.certificate.store', $studentIntakeCourseCompetence->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Certificate</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="certificate_template_id">Certificate Template</label> <span class="required">*</span>
                                    <select id="certificate_template_id" name="certificate_template_id" class="form-control">
                                        <option value="" selected disabled>-- Select Certificate Template --</option>
                                        @foreach($templates as $key => $value)
                                        <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="certificate_type">Certificate Type</label> <span class="required">*</span>
                                    <input type="text" name="certificate_type" class="form-control" id="certificate_type" placeholder="Enter Certificate Type">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="expiry_date">Issued Date</label> <span class="required">*</span>
                                    <input type="date" name="expiry_date" class="form-control" id="expiry_date" placeholder="Enter Issued Date">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->

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
        $('#addCertificate').validate({
            rules: {
                certificate_template_id: {
                    required: true,
                },
                certificate_type: {
                    required: true,
                },
                expiry_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                certificate_template_id: "Please choose one certificate template",
                certificate_type: "Please enter certificate type",
                expiry_date: "Please enter expiry date",
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
</script>
@endsection
