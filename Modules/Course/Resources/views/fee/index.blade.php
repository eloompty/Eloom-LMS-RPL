@extends('user::layouts.master')
@section('title', 'Admin | Course Fees')

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
                <h1>{{ $course->course_name }} Fees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item active">Fees</li>
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
                        <h3 class="card-title">List of {{ $course->course_name }}'s fees</h3>
                        <div class="col-md-12 text-right"><a @if ($course->registered == 1) href="{{ route('admin.course.fee.create', $course->id) }}" @else href="{{ route('admin.unregistered.fee.create', $course->id) }}" @endif class="btn btn-success">Add Fee</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($fees) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Initial Fee</th>
                                    <th>Enrollment Fee</th>
                                    <th>Enrollment Fee Wavier</th>
                                    <th>Material Fee</th>
                                    <th>Material Fee Wavier</th>
                                    <th>Fee</th>
                                    <th>Installment</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fees as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->initial_fee }}</td>
                                    <td>{{ $value->enrollment_fee }}</td>
                                    <td>@if ($value->enrollment_fee_wavier == 1) Yes @else No @endif</td>
                                    <td>{{ $value->material_fee }}</td>
                                    <td>@if ($value->material_fee_wavier == 1) Yes @else No @endif</td>
                                    <td>{{ $value->fee }}</td>
                                    <td>{{ $value->installment }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a  @if ($course->registered == 1) href="{{ route('admin.course.fee.edit', $value->id) }}" @else href="{{ route('admin.unregistered.fee.edit', $value->id) }}" @endif class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.course.fee.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Initial Fee</th>
                                    <th>Enrollment Fee</th>
                                    <th>Enrollment Fee Wavier</th>
                                    <th>Material Fee</th>
                                    <th>Material Fee Wavier</th>
                                    <th>Fee</th>
                                    <th>Installment</th>
                                    <th>Type</th>
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