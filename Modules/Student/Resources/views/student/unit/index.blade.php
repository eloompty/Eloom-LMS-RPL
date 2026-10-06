@extends('student::student.layouts.master')
@section('title', 'Student | Units')

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
                <h1>{{ $course->intakeCourse->course->course_name }}'s Units</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Course</a></li>
                    <li class="breadcrumb-item active">Units</li>
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
                        <h3 class="card-title">List of @if(count($units) > 0) {{ $course->intakeCourse->course->course_name }}'s @endif Units</h3>
                    </div>
                    <!-- @php
                    $competence = $course->studentIntakeCourseCompetence;
                    if ($competence == NULL) {
                    $award_status = $certificate_type = $parchment_issue_date = $parchment_no = NULL;
                    } else {
                        if ($course->studentIntakeCourseCompetence->award_status == 'Y') $award_status = 'Yes';
                        else $award_status = 'No';
                        $certificate_type = $course->studentIntakeCourseCompetence->certificate_type;
                        $parchment_issue_date = $course->studentIntakeCourseCompetence->parchment_issue_date;
                        $parchment_no = $course->studentIntakeCourseCompetence->parchment_no;
                    }
                    @endphp -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Results</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-sm-3">
                                                <label for="award_status">Award Status</label>
                                                <input type="text" name="award_status" class="form-control" id="award_status" placeholder="Enter Certificate Type" value="{{ $award_status }}" readonly>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label for="certificate_type">Certificate Type</label>
                                                <input type="text" name="certificate_type" class="form-control" id="certificate_type" placeholder="Enter Certificate Type" value="{{ $certificate_type }}" readonly>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label for="parchment_issue_date">Certificate Issue Date</label>
                                                <input type="date" name="parchment_issue_date" class="form-control" id="parchment_issue_date" placeholder="Enter Certificate Issue Date" value="{{ $parchment_issue_date }}" readonly>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label for="parchment_no">Certificate No</label>
                                                <input type="text" name="parchment_no" class="form-control" id="parchment_no" placeholder="Enter Certificate No" value="{{ $parchment_no }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @if(count($units) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Is Locked</th>
                                    <th>Unit Status</th>
                                    <th>Outcome</th>
                                    <th>Time Table</th>
                                    <th>Resources</th>
                                    <th>Submissions</th>
                                    <th>Online Classes</th>
                                    @if(attendanceSetting('display_student_attendance')=='on')<th>Attendance</th>@endif
                                    <th>Group Chats</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeUnit->unit->code }}</td>
                                    <td>{{ $value->intakeUnit->unit->name }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1 && $value->is_complete == 1)
                                        <span class="status active">Completed</span>
                                        @elseif ($value->status == 1 && $value->is_complete == 0)
                                        <span class="status deleted">Running</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>{{ getIdentifierValue('OUTCOME IDENTIFIER - NATIONAL', $value->outcome) }}</td>
                                    <td>
                                        @if (count($value->intakeUnitTime) > 0)
                                        <table>
                                            @foreach($value->intakeUnitTime as $time)
                                            <tr>
                                                <b>{{ $time->day }} :</b> {{ timeFormat($time->from) }}-{{ timeFormat($time->to) }}@if($time->classroom != NULL)({{ $time->classroom }})@endif <br>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                    </td>
                                    <td>@if ($value->status == 1)<a href="{{ route('student.resource.index', $value->id) }}" class="btn btn-info btn-sm"> Resources</a>@endif</td>
                                    <td>@if ($value->status == 1)<a href="{{ route('student.assignment.index', $value->id) }}" class="btn btn-info btn-sm"> Assignment</a>@endif</td>
                                    <td>
                                        @if ($value->status == 1)
                                        <a href="{{ route('student.onlineclass.index', $value->id) }}" class="btn btn-info btn-sm"> Zoom</a>
                                        <a href="{{ route('student.team.index', $value->id) }}" class="btn btn-info btn-sm"> Teams</a>
                                        @endif
                                    </td>
                                    @if(attendanceSetting('display_student_attendance')=='on')<td>@if ($value->status == 1)<a href="{{ route('student.attendance.index', $value->id) }}" class="btn btn-info btn-sm"> Attendance</a>@endif</td>@endif
                                    <td>@if ($value->status == 1)<a href="{{ route('student.unit.chat.index', $value->id) }}" class="btn btn-info btn-sm">Group Chats</a>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Is Locked</th>
                                    <th>Unit Status</th>
                                    <th>Outcome</th>
                                    <th>Time Table</th>
                                    <th>Resources</th>
                                    <th>Submissions</th>
                                    <th>Online Classes</th>
                                    @if(attendanceSetting('display_student_attendance')=='on')<th>Attendance</th>@endif
                                    <th>Group Chats</th>
                                </tr>
                            </tfoot>
                        </table>
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
