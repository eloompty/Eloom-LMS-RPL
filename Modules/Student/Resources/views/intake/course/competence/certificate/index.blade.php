@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Course Competence Certificates')

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
                <h1>Student Intake Course Competence Certificates</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeCourseCompetence->studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.competence.index', $studentIntakeCourseCompetence->student_intake_course_id) }}">Competence</a></li>
                    <li class="breadcrumb-item active">Certificates</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $studentIntakeCourseCompetence->studentIntakeCourse->student_id) }}'s Certificates</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.intake.course.competence.certificate.create', $studentIntakeCourseCompetence->id) }}" class="btn btn-success">Add Certificate</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($certificates) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Certificate Name</th>
                                    <th>Certificate Type</th>
                                    <th>Issued Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($certificates as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->certifateTemplate->name }}</td>
                                    <td>{{ $value->certificate_type }}</td>
                                    <td data-sort='{{ convertDate($value->expiry_date) }}'>{{ dateFormat($value->expiry_date) }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                    <a href="{{ route('admin.student.intake.course.competence.certificate.edit', $value->id) }}" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    <a href="{{ route('admin.student.intake.course.competence.certificate.print', $value->id) }}" class="btn btn-info btn-sm" title="Print"><i class="fas fa-print-alt"></i> Print</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.student.intake.course.competence.certificate.delete', $value->id) }}" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Certificate Name</th>
                                    <th>Certificate Type</th>
                                    <th>Issued Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
