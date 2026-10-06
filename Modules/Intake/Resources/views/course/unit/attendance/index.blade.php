@extends('user::layouts.master')
@section('title', 'Admin | Attendance')

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
                <h1>Student Attendance of {{ $month }}, {{ $year }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeUnit->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $intakeUnit->id) }}">Units</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
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
                        <h3 class="card-title">{{ $intakeUnit->unit->name }}'s Unit Student Attendance</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="text-center m-3">
                            <form action="">
                                <input type="hidden" id="intakeId" value="{{ $intakeUnit->id }}">
                                <div class="row">
                                    <div class="col-4">
                                        <select name="year" class="form-control" id="year">
                                            <option value="" selected disabled>-- Select Year --</option>
                                            @foreach($intakeYears as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select name="month" class="form-control" id="month">
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <form action="{{ route('admin.intake.unit.attendance.store', $intakeUnit->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 85px">Students/Days</th>
                                            @for($i = 1; $i <= $daysInMonth; $i++) <th style="width: 5px">{{ $i }}</th> @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                        <tr>
                                            <td>{{ userName('Student', $student->studentIntakeCourse->student_id) }}</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                            <td style="width: 5px">
                                                <input type="checkbox" name="{{ $student->studentIntakeCourse->student_id }}[]" value="{{ $day = now()->setYear($year)->setMonth($month)->setDay($i)->format('Y-m-d') }}" {{ isset($attendances[$student->studentIntakeCourse->student_id][$day]) ? 'checked' : '' }}>
                                            </td>
                                            @endfor
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <input class="btn btn-primary" type="submit" value="Save Attendance">
                        </form>
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
    $('#year').change(function() {
        var year = $(this).val();
        var intakeid = document.getElementById('intakeId').value;
        console.log('intake id: ', intakeid);
        if (year) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/intake/course/unit/attendance/year')}}?year=" + year + "&intakeid=" + intakeid,
                success: function(res) {
                    if (res) {
                        $("#month").empty();
                        $("#month").append('<option value="">-- Select Month --</option>');
                        $.each(res, function(key, value) {
                            $("#month").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#month").empty();
                    }
                }
            });
        } else {
            $("#month").empty();
        }
    });

    $('#month').change(function() {
        var month = $(this).val();
        var intakeid = document.getElementById('intakeId').value;
        var year = document.getElementById('year').value;
        console.log('intake id: ', intakeid);
        console.log('year: ', year);
        console.log('month: ', month);

        var url = "{{url('admin/intake/course/unit/attendance')}}" + "/" + intakeid + "/" + year + "/" + month;
        console.log('url: ', url);
        window.location = url;
    });
</script>
@endsection