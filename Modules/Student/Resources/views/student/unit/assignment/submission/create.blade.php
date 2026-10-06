@extends('student::student.layouts.master')
@section('title', 'Student | Add Assignment Submission')

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
                <h1>Add Assignment Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntakeUnit->student_intake_course_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.assignment.index', $studentIntakeUnit->intake_unit_id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.submission.index', [$assignment->id, $studentIntakeUnit->id]) }}">Submissions</a></li>
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
        <form id="addassignmentsubmission" action="{{ route('student.submission.store', [$assignment->id, $studentIntakeUnit->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Assignment Submission</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <input type="hidden" name="assignment_resubmission_id" value="{{ $resubmission_id }}">
                            <div class="form-group">
                                <label for="type">Document Type</label> <span class="required">*</span>
                                <select name="document_type" class="form-control" id="document_type" onchange="yesnoCheck(this);">
                                    <option value="" selected disabled>-- Select Document Type --</option>
                                    @foreach($document_types as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="ifNo" style="display:none;">
                                <label for="name">Video Link</label> <span class="required">*</span>
                                <input type="text" name="video_link" class="form-control" id="video_link" placeholder="Enter Video Link">
                            </div>
                            <div id="ifYes" style="display:block;">
                                <div class="form-group">
                                    <label for="path">Assignment</label> <span class="required">*</span>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="path" class="custom-file-input" id="path" onchange="readURL(this);">
                                            <label class="custom-file-label" for="path">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                </div>
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
                <button type="submit" class="btn btn-primary" onclick="return confirmation();">Submit</button>
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
        $('#addassignmentsubmission').validate({
            rules: {
                path: {
                    required: true,
                },
                video_link: {
                    required: true,
                },
                document_type: {
                    required: true,
                },
            },
            messages: {
                path: "Please upload assignment",
                document_type: "Please one document_type",
                video_link: "Please enter assignment video link",
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
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function yesnoCheck(that) {
        if (that.value == "Video Link") {
            document.getElementById("ifNo").style.display = "block";
            document.getElementById("ifYes").style.display = "none";
        } else {
            document.getElementById("ifNo").style.display = "none";
            document.getElementById("ifYes").style.display = "block";
        }
    }

    let x = "Course: {{ $assignment->intakeUnit->intakeCourse->course->course_name }}\nUnit: {{ $assignment->intakeUnit->unit->name }}\nAssignment: {{ $assignment->name }}\nAre your sure, you want to submit?";

    function confirmation() {
        var text = x;
        if (confirm(text)) {
            $('#addassignmentsubmission').validate();
        } else {
            return false;
        }
    }
</script>
@endsection
