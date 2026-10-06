@extends('user::layouts.master')
@section('title', 'Admin | Add Unit')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Unit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($course->registered == 1) href="{{ route('admin.course.unit.index', $course->id) }}" @else href="{{ route('admin.unregistered.unit.index', $course->id) }}" @endif>Units</a></li>
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
        <form id="addunit" @if ($course->registered == 1) action="{{ route('admin.course.unit.store', $course->id) }}" @else action="{{ route('admin.unregistered.unit.store', $course->id) }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Unit</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if ($course->registered == 1)
                            <div class="form-group">
                                <label for="code">Unit Code</label> <span class="required">*</span>
                                <input type="text" name="code" class="form-control" id="code" placeholder="Enter Unit Code">
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="name">Unit Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Unit Name">
                            </div>
                            <div class="form-group">
                                <label for="type">Unit Type</label> <span class="required">*</span>
                                <select name="type" class="form-control" id="type">
                                    <option value="" selected disabled>-- Select Unit Type --</option>
                                    <option value="Core">Core</option>
                                    <option value="Elective">Elective</option>
                                </select>
                            </div>
                            @if ($course->registered == 1)
                            <div class="form-group">
                                <label for="type">Education Field</label> <span class="required">*</span>
                                <select name="education_field" class="form-control" id="education_field">
                                    <option value="" selected disabled>-- Select Education Field --</option>
                                    @foreach(getIdentifiers('EDUCATION FIELD') as $education_field)
                                    <option value="{{ $education_field->value }}">{{ $education_field->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="hours">Hours</label>
                                <input type="number" name="hours" class="form-control" id="hours" placeholder="Enter Hours">
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration In Weeks</label>
                                <input type="number" name="duration" class="form-control" id="duration" placeholder="Enter Duration In Weeks">
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date In Weeks</label>
                                <input type="number" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date In Weeks">
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
        $('#addunit').validate({
            rules: {
                code: {
                    required: true,
                },
                name: {
                    required: true,
                },
                type: {
                    required: true,
                },
                education_field: {
                    required: true,
                },
                hours: {
                    digits: true,
                    maxlength: 4
                },
                status: {
                    required: true
                },
            },
            messages: {
                code: "Please enter unit code",
                name: "Please enter unit name",
                type: "Please select unit type",
                education_field: "Please select education field",
                hours: {
                    maxlength: "Hours cannot be more than 4 digits"
                },
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