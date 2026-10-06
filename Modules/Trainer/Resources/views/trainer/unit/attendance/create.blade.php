@extends('trainer::trainer.layouts.master')
@section('title', "Trainer | Take Today's Attendance")

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
                <h1>Take Today's Attendance</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Course</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_course_id) }}">Unit</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.attendance.index', [$trainerIntake->intake_unit_id, date('Y'), date('m')]) }}">Attendace</a></li>
                    <li class="breadcrumb-item active">Add Attendance</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Take Today's Attendance</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($students) > 0)
                        <form action="{{ route('trainer.attendance.store', $trainerIntake->intake_unit_id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="icheck-info d-inline">
                                    <input type="checkbox" class="form-controll select_all" id="checkboxInfo">
                                    <label for="checkboxInfo" class="check view">Select All</label>
                                </div>
                            </div>
                            <table id="" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $value)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ userName('Student', $value->studentIntakeCourse->student_id) }}</td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" class="checkbox" id="checkboxSuccess{{ $value->studentIntakeCourse->student_id }}" name="student_id[]" value="{{ $value->studentIntakeCourse->student_id }}">
                                                <label for="checkboxSuccess{{ $value->studentIntakeCourse->student_id }}" class="check add"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Attendace</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Attendace</button>
                            </div>
                        </form>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('scripts')
<script>
    $('.select_all').on('change', function() {
        $('.checkbox').prop('checked', $(this).prop("checked"));
    });
    //deselect "checked all", if one of the listed checkbox category is unchecked amd select "checked all" if all of the listed checkbox category is checked
    $('.checkbox').change(function() { //".checkbox" change 
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('.select_all').prop('checked', true);
        } else {
            $('.select_all').prop('checked', false);
        }
    });
</script>
@endsection