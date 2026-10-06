@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Unit')

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
                <h1>Student Intake Unit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeUnit->studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.unit.index', $studentIntakeUnit->student_intake_course_id) }}">Units</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Edit <small>Student Intake Unit</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editIntakeUnit" action="{{ route('admin.student.intake.unit.update', $studentIntakeUnit->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="unit">Unit</label> <span class="required">*</span>
                                    <input type="text" class="form-control" id="unit" disabled value="{{ $studentIntakeUnit->intakeUnit->unit->name }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="duration">Duration</label> <span class="required">*</span>
                                    <input type="number" name="duration" class="form-control" id="duration" disabled value="{{ $studentIntakeUnit->duration }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="funding_source_national">Funding Source National</label>
                                    <select class="form-control" name="funding_source_national" id="funding_source_national">
                                        <option value="">-- Select Funding Source National --</option>
                                        @foreach(getIdentifiers('FUNDING SOURCE - NATIONAL') as $funding_source_national)
                                        <option value="{{ $funding_source_national->value }}" @if($funding_source_national->value == $studentIntakeUnit->funding_source_national) selected @endif>{{ $funding_source_national->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="funding_source_state_training_authority">Funding Source State Training Authority</label>
                                    <input type="text" name="funding_source_state_training_authority" class="form-control" id="funding_source_state_training_authority" placeholder="Enter Funding Source State Training Authority" value="{{ $studentIntakeUnit->funding_source_state_training_authority }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="delivery_mode">Delivery Mode</label>
                                    <select name="delivery_mode" class="form-control" id="delivery_mode">
                                        <option value="" selected disabled>-- Select Delivery Mode --</option>
                                        @foreach(getIdentifiers('DELIVERY MODE IDENTIFIER') as $delivery_mode_identifier)
                                        <option value="{{ $delivery_mode_identifier->value }}" @if($delivery_mode_identifier->value == $studentIntakeUnit->delivery_mode) selected @endif>{{ $delivery_mode_identifier->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="predominant_delivery_mode">Predominant Delivery Mode</label>
                                    <select name="predominant_delivery_mode" class="form-control" id="predominant_delivery_mode">
                                        <option value="" selected disabled>-- Select Predominant Delivery Mode --</option>
                                        @foreach(getIdentifiers('PREDOMINANT DELIVERY MODE') as $predominant_delivery_mode)
                                        <option value="{{ $predominant_delivery_mode->value }}" @if($predominant_delivery_mode->value == $studentIntakeUnit->predominant_delivery_mode) selected @endif>{{ $predominant_delivery_mode->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="commencing">Commencing Program</label>
                                    <select name="commencing" class="form-control" id="commencing">
                                        <option value="" selected disabled>-- Select Commencing Program --</option>
                                        @foreach(getIdentifiers('COMMENCING PROGRAM IDENTIFIER') as $commencing)
                                        <option value="{{ $commencing->value }}" @if($commencing->value == $studentIntakeUnit->commencing) selected @endif>{{ $commencing->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="starting_date">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $studentIntakeUnit->starting_date }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="ending_date">Ending Date</label>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $studentIntakeUnit->ending_date }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $studentIntakeUnit->due_date }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($studentIntakeUnit->status == '1')selected @endif value="1">Active</option>
                                        <option @if($studentIntakeUnit->status == '0')selected @endif value="0">Inactive</option>
                                        <option @if($studentIntakeUnit->status == '3')selected @endif value="3">Locked</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="is_complete">Unit Status</label> <span class="required">*</span>
                                    <select name="is_complete" class="form-control" id="is_complete">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($studentIntakeUnit->is_complete == '1')selected @endif value="1">Completed</option>
                                        <option @if($studentIntakeUnit->is_complete == '0')selected @endif value="0">Running</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="outcome">Outcome Identifier</label>
                                    <select name="outcome" class="form-control" id="outcome">
                                        <option value="" selected disabled>-- Select Outcome Identifier --</option>
                                        @foreach(getIdentifiers('OUTCOME IDENTIFIER - NATIONAL') as $outcome)
                                        <option value="{{ $outcome->value }}" @if($outcome->value == $studentIntakeUnit->outcome) selected @endif>{{ $outcome->description }}</option>
                                        @endforeach
                                    </select>
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
        $('#editIntakeUnit').validate({
            rules: {
                status: {
                    required: true
                },
            },
            messages: {
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