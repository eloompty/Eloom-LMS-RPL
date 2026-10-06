@extends('user::layouts.master')
@section('title', 'Admin | Add Offer Letter')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Offer Letter</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.offer.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.offer.letter.index', $student->id) }}">Offer Letters</a></li>
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
        <form id="addstudentoffer" action="{{ route('admin.student.offer.letter.store', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Create {{ userName('Student', $student->id) }}'s Offer letter</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control" id="expiry_date" placeholder="Enter Expiry Date">
                            </div>
                            <div class="form-group">
                                <label for="intake_course_ids">Choose Intake Courses</label> <span class="required">*</span>
                                @foreach($intake_courses as $key => $value)
                                <div class="form-group">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="{{ $key }}" name="intake_course_ids[]" value="{{ $value->intake_course_id }}" checked>
                                        <label for="{{ $key }}" class="check">{{ $value->intakeCourse->course->course_name }}   ({{ $value->intakeCourse->intake->name }})</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="condition">Condition</label>
                                <select name="condition" class="form-control" id="condition">
                                    <option value="" selected disabled>-- Select Condition --</option>
                                    @foreach ($conditions as $condition)
                                    <option value="{{ $condition->id }}">{{ $condition->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="credit">Credit</label>
                                <select name="credit" class="form-control" id="credit">
                                    <option value="" selected disabled>-- Select Credit --</option>
                                    @foreach ($credits as $credit)
                                    <option value="{{ $credit->id }}">{{ $credit->title }}</option>
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
        $('#addstudentoffer').validate({
            rules: {
                "intake_course_ids[]": {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                status: "Please select one status",
                "intake_course_ids[]": "Please select atleast one intake course",
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