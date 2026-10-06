@extends('user::layouts.master')
@section('title', 'Admin | Tickets')

@section('content')
<style>
    .items-container {
        margin: 30px 17px 20px;
        max-width: 800px;
        text-align: center;
    }

    .items-container a {
        color: #173649;
        font-size: 18px;
        justify-content: center;
        text-align: center;
    }

    .main-item {
        width: 180px;
    }

    .nav-link:focus,
    .nav-link:hover,
    .main-item.active a {
        color: #545cd8;
        border-bottom: 2px solid #545cd8;
    }

    .items {
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        border-bottom: 1px solid #ccc;
        display: flex;
        flex-wrap: wrap;
    }
</style>
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
                <h1>Tickets</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Tickets</li>
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
                    <div class="items-container">
                        <ul class="items">
                            <li class="main-item {{ ($status == 1) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.ticket.index') }}?status=1">Opened</a>
                            </li>
                            <li class="main-item {{ ($status == 2) ? 'active' : '' }}"">
                                <a class=" nav-link" href="{{ route('admin.ticket.index') }}?status=2">In Progress</a>
                            </li>
                            <li class="main-item {{ ($status == 3) ? 'active' : '' }}"">
                                <a class=" nav-link" href="{{ route('admin.ticket.index') }}?status=3"">Closed</a>
                            </li>
                            <li class=" main-item {{ ($status == 4) ? 'active' : '' }}"">
                                <a class="nav-link" href="{{ route('admin.ticket.index') }}?status=4">Reopened</a>
                            </li>
                        </ul>

                    </div>
                    <div class="card-header">
                        <h3 class="card-title">List of {{ getTicketStatus($status) }} Tickets</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($tickets) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Opened Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->subject }}</td>
                                    <td>{{ getTicketStatus($value->status) }}</td>
                                    <td>{{ dateFormat($value->created_at) }}</td>
                                    <td><a href="{{ route('admin.ticket.show', $value->id) }}" class="btn btn-info">View</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Opened Date</th>
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