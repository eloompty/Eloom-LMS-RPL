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
                    <li class="breadcrumb-item"><a @if ($studentIntakeUnit->studentIntakeCourse->student->is_enrolled == 0) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeUnits[0]->studentIntakeCourse->student_id) }}">Intakes</a></li>
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
                        <h3 class="card-title">List of {{ userName('Student', $studentIntakeUnit->studentIntakeCourse->student_id) }}'s Intake Unit</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($studentIntakeUnits) > 0)
                        <form action="{{ route('admin.student.intake.unit.bulkupdate', $studentIntakeUnit->student_intake_course_id) }}" method="POST">
                            @csrf
                            <table id="example1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Unit</th>
                                        <th>Funding Source National</th>
                                        <th>Funding Source State Training Authority</th>
                                        <th>Delivery Mode</th>
                                        <th>Internal</th>
                                        <th>Predominant Delivery Mode</th>
                                        <th>Duration</th>
                                        <th>Starting Date</th>
                                        <th>Ending Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Unit Status</th>
                                        <th>Outcome</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentIntakeUnits as $index => $value)
                                    <tr>
                                        <td>{{ $no++ }}<input type="hidden" name="{{$value->id}}[id]" value="{{ $value->id }}"></td>
                                        <td>{{ $value->intakeUnit->unit->code }}</td>
                                        <td>{{ $value->intakeUnit->unit->name }}</td>
                                        <!-- <td>{{ getIdentifierValue('FUNDING SOURCE - NATIONAL', $value->funding_source_national) }}</td> -->
                                        <td>
                                            <select class="form-control" name="{{$value->id}}[funding_source_national]" id="funding_source_national">
                                                <option value="">-- Select Funding Source National --</option>
                                                @foreach(getIdentifiers('FUNDING SOURCE - NATIONAL') as $funding_source_national)
                                                <option value="{{ $funding_source_national->value }}" @if($funding_source_national->value == $value->funding_source_national) selected @endif>{{ $funding_source_national->description }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <!-- <td>{{ $value->funding_source_state_training_authority }}</td> -->
                                        <td><input type="text" name="{{$value->id}}[funding_source_state_training_authority]" class="form-control" id="funding_source_state_training_authority" value="{{ $value->funding_source_state_training_authority }}"></td>
                                        <!-- <td>{{ getIdentifierValue('DELIVERY MODE IDENTIFIER', $value->delivery_mode) }}</td> -->
                                        <td>
                                            <select name="{{$value->id}}[delivery_mode]" class="form-control" id="delivery_mode">
                                                <option value="" selected disabled>-- Select Delivery Mode --</option>
                                                @foreach(getIdentifiers('DELIVERY MODE IDENTIFIER') as $delivery_mode_identifier)
                                                <option value="{{ $delivery_mode_identifier->value }}" @if($delivery_mode_identifier->value == $value->delivery_mode) selected @endif>{{ $delivery_mode_identifier->description }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="checkbox" disabled @if ($value->internal == 'Yes') checked @endif></td>
                                        <!-- <td>{{ getIdentifierValue('PREDOMINANT DELIVERY MODE', $value->predominant_delivery_mode) }}</td> -->
                                        <td>
                                            <select name="{{$value->id}}[predominant_delivery_mode]" class="form-control" id="predominant_delivery_mode">
                                                <option value="" selected disabled>-- Select Predominant Delivery Mode --</option>
                                                @foreach(getIdentifiers('PREDOMINANT DELIVERY MODE') as $predominant_delivery_mode)
                                                <option value="{{ $predominant_delivery_mode->value }}" @if($predominant_delivery_mode->value == $value->predominant_delivery_mode) selected @endif>{{ $predominant_delivery_mode->description }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $value->duration }}</td>
                                        <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                        <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                        <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                        <td>
                                            <select name="{{$value->id}}[status]" class="form-control" id="status">
                                                <option value="" selected disabled>-- Select Status --</option>
                                                <option @if($value->status == '1')selected @endif value="1">Active</option>
                                                <option @if($value->status == '0')selected @endif value="0">Inactive</option>
                                                <option @if($value->status == '3')selected @endif value="3">Locked</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="{{$value->id}}[is_complete]" class="form-control" id="is_complete">
                                                <option value="" selected disabled>-- Select Status --</option>
                                                <option @if($value->is_complete == '1')selected @endif value="1">Completed</option>
                                                <option @if($value->is_complete == '0')selected @endif value="0">Running</option>
                                            </select>
                                        </td>
                                        <!-- <td>{{ getIdentifierValue('OUTCOME IDENTIFIER - NATIONAL', $value->outcome) }}</td> -->
                                        <td>
                                            <select name="{{$value->id}}[outcome]" class="form-control" id="outcome">
                                                <option value="" selected disabled>-- Select Outcome --</option>
                                                @foreach(getIdentifiers('OUTCOME IDENTIFIER - NATIONAL') as $outcome)
                                                <option value="{{ $outcome->value }}" @if($outcome->value == $value->outcome) selected @endif>{{ $outcome->description }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.student.intake.unit.submission.index', $value->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-list"></i> Submission</a>
                                            <a href="{{ route('admin.student.intake.unit.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                            @if ($value->status != 2)
                                            <a href="{{ route('admin.student.intake.unit.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Unit</th>
                                        <th>Funding Source National</th>
                                        <th>Funding Source State Training Authority</th>
                                        <th>Delivery Mode</th>
                                        <th>Internal</th>
                                        <th>Predominant Delivery Mode</th>
                                        <th>Duration</th>
                                        <th>Starting Date</th>
                                        <th>Ending Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Unit Status</th>
                                        <th>Outcome</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="submit" class="btn btn-info">Edit All</button>
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