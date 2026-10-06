@extends('trainer::trainer.layouts.master')
@section('title', 'Trainer | Add Resource')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $unit->name }}'s Resource</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_course_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.resource.index', $trainerIntake->id) }}">Resources</a></li>
                    <li class="breadcrumb-item active">Add  Unit Resource</li>
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
                        <h3 class="card-title">Add <small>{{ $unit->name }}'s Resource</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addresource" action="{{ route('trainer.resource.store', [$unit->id, $trainer_intake_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="resource_type">Type</label> <span class="required">*</span>
                                <select name="resource_type" class="form-control" id="resource_type" onchange="yesnoCheck(this);">
                                    <option value="" selected disabled>-- Select Type --</option>
                                    @foreach ($document_types as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="ifNo" style="display:none;">
                                <label for="name">Video Link</label> <span class="required">*</span>
                                <input type="text" name="file" class="form-control" id="name" placeholder="Enter Video Link">
                            </div>
                            <div  id="ifYes" style="display:block;">
                                <div class="form-group">
                                    <label for="file">Upload File</label> <span class="required">*</span>
                                    <div class="input-group hdtuto control-group lst increment">
                                        <input type="file" name="file[]" class="form-control">
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="clone hide">
                                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                            <input type="file" name="file[]" class="form-control">
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="resource_category_id">Resource Categories</label> <span class="required">*</span>
                                <select id="resource_category_id" name="resource_category_id" class="form-control">
                                    <option value="" selected disabled>-- Select Resource Categories --</option>
                                    @foreach($categories as $key => $value)
                                    <option value="{{$key}}"> {{$value}}</option>
                                    @endforeach
                                </select>
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
        $('#addresource').validate({
            rules: {
                name: {
                    required: true,
                },
                resource_type: {
                    required: true,
                },
                file: {
                    required: true,
                },
                resource_category_id: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter category name",
                resource_type: "Please one resource type",
                file: "Please upload file",
                resource_category_id: "Please choose one category",
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

    $(document).ready(function() {
        $(".btn-success").click(function() {
            var lsthmtl = $(".clone").html();
            $(".increment").after(lsthmtl);
        });
        $("body").on("click", ".btn-danger", function() {
            $(this).parents(".hdtuto").remove();
        });
    });

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