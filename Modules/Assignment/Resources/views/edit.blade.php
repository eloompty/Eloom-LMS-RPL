@extends('user::layouts.master')
@section('title', 'Admin | Edit Assignment')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.assignment.index') }}">Assignments</a></li>
                    <li class="breadcrumb-item active">Edit Assignment</li>
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
                        <h3 class="card-title">Edit <small>{{ $assignment->name }}'s Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addassignment" action="{{ route('admin.assignment.update', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $assignment->name }}">
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label> <span class="required">*</span>
                                <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $assignment->due_date }}">
                            </div>
                            @if ($assignment->type == 'file')
                            <div class="form-group">
                                <label for="type">Document Type</label> <span class="required">*</span>
                                <select name="document_type" class="form-control" id="document_type" disabled>
                                    <option value="" selected disabled>-- Select Document Type --</option>
                                    @foreach($document_types as $type)
                                    <option value="{{ $type->name }}" @if ($assignment->document_type == $type->name) selected @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($assignment->document_type == 'Video Link')
                            <div class="form-group">
                                <label for="name">Video Link</label> <span class="required">*</span>
                                <input type="text" name="path" class="form-control" id="path" placeholder="Enter Video Link" value="{{ $assignment->path }}" required>
                            </div>
                            @else
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
                                @if ($assignment->type == 'file')
                                <img src="{{ asset($assignment->path) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                @elseif ($assignment->type == 'mcq')
                                <a href="{{ route('admin.assignment.mcq', $assignment->id) }}"><img src="{{ asset('files/mcq.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @else
                                <a href="{{ route('admin.assignment.question', $assignment->id) }}"><img src="{{ asset('files/qa.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @endif
                            </div>
                            @endif
                            @endif
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($assignment->status == '1')selected @endif value="1">Active</option>
                                    <option @if($assignment->status == '0')selected @endif value="0">Inactive</option>
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
        $('#addassignment').validate({
            rules: {
                name: {
                    required: true,
                },
                due_date: {
                    required: true,
                },
            },
            messages: {
                name: "Please enter name",
                due_date: "Please enter due date",
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
</script>
@endsection