@extends('user::layouts.master')
@section('title', 'Admin | Course Units')

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
                <h1>{{ $course->course_name }} Units</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
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
                        <h3 class="card-title">List of {{ $course->course_name }}'s units</h3>
                        <div class="col-md-12 text-right"><a @if ($course->registered == 1) href="{{ route('admin.course.unit.create', $course->id) }}" @else href="{{ route('admin.unregistered.unit.create', $course->id) }}" @endif class="btn btn-success">Add Unit</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($units) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ($course->registered == 1)<th>Code</th>@endif
                                    <th>Name</th>
                                    <th>Type</th>
                                    @if ($course->registered == 1)<th>Education Field</th>@endif
                                    <th>Hours</th>
                                    <th>Duration</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    @if ($course->registered == 1)<td>{{ $value->code }}</td>@endif
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->type }}</td>
                                    @if ($course->registered == 1)<td>{{ getIdentifierValue('EDUCATION FIELD', $value->education_field) }}</td>@endif
                                    <td>{{ $value->hours }}</td>
                                    <td>{{ $value->duration }}</td>
                                    <td>{{ $value->due_date }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a @if ($course->registered == 1) href="{{ route('admin.course.unit.resource.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.resource.index', $value->id) }}" @endif class="btn btn-warning btn-sm"><i class="fas fa-clipboard"></i> Resources</a>
                                        <a @if ($course->registered == 1) href="{{ route('admin.course.unit.assignment.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.assignment.index', $value->id) }}" @endif class="btn btn-primary btn-sm"><i class="fas fa-chart-pie"></i> Assignment</a>
                                        <a @if ($course->registered == 1) href="{{ route('admin.course.unit.edit', $value->id) }}" @else href="{{ route('admin.unregistered.unit.edit', $value->id) }}" @endif class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a @if ($course->registered == 1) href="{{ route('admin.course.unit.missing.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.missing.index', $value->id) }}" @endif class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> Add Missing</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.course.unit.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    @if ($course->registered == 1)<th>Code</th>@endif
                                    <th>Name</th>
                                    <th>Type</th>
                                    @if ($course->registered == 1)<th>Education Field</th>@endif
                                    <th>Hours</th>
                                    <th>Duration</th>
                                    <th>Due Date</th>
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