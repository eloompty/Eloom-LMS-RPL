@extends('trainer::trainer.layouts.master')
@section('title', 'Trainer | Add Assignment')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $trainerIntake->intakeUnit->unit->name }}'s Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_course_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item active">Add Unit Assignment</li>
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
                        <h3 class="card-title">Add <small>{{ $trainerIntake->intakeUnit->unit->name }}'s Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addassignment" action="{{ route('trainer.assignment.store', $trainerIntake->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label> <span class="required">*</span>
                                <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date">
                            </div>
                            <!-- <div class="form-group">
                                <label for="type">Assignment Type</label> <span class="required">*</span>
                                <select name="type" class="form-control" id="type" onchange="yesnoCheck(this);">
                                    <option value="" selected disabled>-- Select Assignment Type --</option>
                                    <option value="file">File</option>
                                    <option value="mcq">Multiple Choice Question</option>
                                    <option value="question">Question</option>
                                </select>
                            </div> -->
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
                                    <label for="path">Upload File</label> <span class="required">*</span>
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
        $('#addassignment').validate({
            rules: {
                name: {
                    required: true,
                },
                due_date: {
                    required: true,
                },
                document_type: {
                    required: true,
                },
                video_link: {
                    required: true,
                },
                path: {
                    required: true,
                },
            },
            messages: {
                name: "Please enter name",
                due_date: "Please enter due date",
                document_type: "Please choose one document type",
                video_link: "Please enter video link",
                path: "Please upload file",
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
</script>
@endsection