@extends('user::layouts.master')
@section('title', 'Admin | Country Identifiers')

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
                <h1>Country Identifiers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Country Identifiers</li>
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
                        <h3 class="card-title">List of Country Identifier</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($countries) > 0)
                        <form action="{{ route('admin.identifier.country.update.bulk') }}" method="POST">
                            @csrf
                            <table id="example1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>#</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($countries as $index => $value)
                                    <tr>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo{{ $value->id }}" name="id[]" value="{{ $value->id }}">
                                                <label for="checkboxInfo{{ $value->id }}" class="check delete"></label>
                                            </div>
                                        </td>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $value->value }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>
                                            @if ($value->status == 1) <span class="status active">Active</span>
                                            @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                            @else <span class="status deleted">Deleted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Select</th>
                                        <th>#</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" disabled>-- Select Status --</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Update Selected</button>
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