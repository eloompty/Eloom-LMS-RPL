@extends('trainer::trainer.layouts.master')
@section('title', 'Trainer Profile')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset(Auth::guard('trainer')->user()->image) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ userName('Trainer', Auth::guard('trainer')->user()->id) }}</h3>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Country</b> <a class="float-right">{{ Auth::guard('trainer')->user()->address->country->name }}</a>
                            </li>
                        </ul>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ Auth::guard('trainer')->user()->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ Auth::guard('trainer')->user()->phone }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Mobile</strong>
                        <p class="text-muted">{{ Auth::guard('trainer')->user()->mobile }}</p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                        <p class="text-muted">{{ fullAddress('Trainer', Auth::guard('trainer')->user()->id) }}</p>

                        <hr>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#qualification" data-toggle="tab">Qualification</a></li>
                            <li class="nav-item"><a class="nav-link" href="#profession" data-toggle="tab">Professional Development</a></li>
                            <li class="nav-item"><a class="nav-link" href="#workplacement" data-toggle="tab">Work Placement</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="qualification">
                                @if(count($qualifications) > 0)
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Award University</th>
                                            <th>Award Year</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($qualifications as $index => $value)
                                        <tr>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->award_university }}</td>
                                            <td>{{ $value->award_year }}</td>
                                            <td>{{ $value->country->name }}</td>
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
                                            <th>Name</th>
                                            <th>Award University</th>
                                            <th>Award Year</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="profession">
                                @if(count($professions) > 0)
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Duration</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($professions as $index => $value)
                                        <tr>
                                            <td>{{ $value->title }}</td>
                                            <td>{{ $value->duration }}</td>
                                            <td>{{ $value->start_date }}</td>
                                            <td>{{ $value->end_date }}</td>
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
                                            <th>Title</th>
                                            <th>Duration</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="workplacement">
                                @if(count($works) > 0)
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Placement Company Name</th>
                                            <th>Contact Person</th>
                                            <th>Contact Person Mobile</th>
                                            <th>Position</th>
                                            <th>Placement Hours</th>
                                            <th>Placement Description</th>
                                            <th>Site Name</th>
                                            <th>Starting Date</th>
                                            <th>Ending Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($works as $index => $value)
                                        <tr>
                                            <td>{{ $value->placement_company_name }}</td>
                                            <td>{{ $value->contact_person }}</td>
                                            <td>{{ $value->contact_person_mobile }}</td>
                                            <td>{{ $value->position }}</td>
                                            <td>{{ $value->placement_hours }}</td>
                                            <td>{{ $value->placement_description }}</td>
                                            <td>{{ $value->site_name }}</td>
                                            <td>{{ $value->starting_date }}</td>
                                            <td>{{ $value->ending_date }}</td>
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
                                            <th>Placement Company Name</th>
                                            <th>Contact Person</th>
                                            <th>Contact Person Mobile</th>
                                            <th>Position</th>
                                            <th>Placement Hours</th>
                                            <th>Placement Description</th>
                                            <th>Site Name</th>
                                            <th>Starting Date</th>
                                            <th>Ending Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection