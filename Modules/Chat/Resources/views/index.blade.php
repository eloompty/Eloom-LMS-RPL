@extends('user::layouts.master')
@section('title', 'Admin | Chats')

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
                <h1>Chats</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Chats</li>
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
                                <a class="nav-link" href="{{ route('admin.chat.index') }}?status=1">Active</a>
                            </li>
                            <li class="main-item {{ ($status == 0) ? 'active' : '' }}"">
                                <a class=" nav-link" href="{{ route('admin.chat.index') }}?status=0">InActive</a>
                            </li>
                            <li class="main-item {{ ($status == 2) ? 'active' : '' }}"">
                                <a class=" nav-link" href="{{ route('admin.chat.index') }}?status=2"">Deleted</a>
                            </li>
                        </ul>

                    </div>
                    <div class="card-header">
                        <h3 class="card-title">List of {{ getChatStatus($status) }} Chats</h3>
                        @if ($status == 1)<div class="col-md-12 text-right"><a href="{{ route('admin.chat.create') }}" class="btn btn-success">Create Chat</a></div>@endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($chats) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Image</th>
                                    <th>Opened Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chats as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td><a href="{{ asset($value->image) }}" target="_blank"><img src="{{ asset($value->image) }}" alt="" width="48" /></a></td>
                                    <td>{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('admin.chat.show', $value->id) }}" class="btn btn-primary"><i class="fas fa-comment"></i> Messages</a>
                                        @if ($value->type == 'Group')<a href="{{ route('admin.chat.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>@endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Image</th>
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
