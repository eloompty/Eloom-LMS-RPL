@extends('user::layouts.master')
@section('title', 'Admin | Online Class Settings')

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
                <h1>Online Class Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Online Class Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="zoomsetting" action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Zoom Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="zoom_api_url">Zoom API URL</label>
                                        <input type="text" name="zoom_api_url" class="form-control" id="zoom_api_url" placeholder="Enter Zoom API URL" value="{{ getSettingValue('zoom_api_url') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="zoom_api_key">Zoom API Key</label>
                                        <input type="text" name="zoom_api_key" class="form-control" id="zoom_api_key" placeholder="Enter Zoom API Key" value="{{ getSettingValue('zoom_api_key') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="zoom_api_secret">Zoom API Secret</label>
                                        <input type="text" name="zoom_api_secret" class="form-control" id="zoom_api_secret" placeholder="Enter Zoom API Secret" value="{{ getSettingValue('zoom_api_secret') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="zoom_api_jwt">Zoom API JWT</label>
                                        <textarea name="zoom_api_jwt" class="form-control" id="zoom_api_jwt" placeholder="Enter Zoom API JWT">{{ getSettingValue('zoom_api_jwt') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!--/.col (left) -->
        </form>
    </div><!-- /.container-fluid -->

    <div class="container-fluid">
        <!-- form start -->
        <form id="teamsetting" action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Teams Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="microsoft_client_id">Microsoft Client Id</label>
                                        <input type="text" name="microsoft_client_id" class="form-control" id="microsoft_client_id" placeholder="Enter Microsoft Client Id" value="{{ getSettingValue('microsoft_client_id') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="microsoft_client_secret">Microsoft Client Secret</label>
                                        <input type="text" name="microsoft_client_secret" class="form-control" id="microsoft_client_secret" placeholder="Enter Microsoft Client Secret" value="{{ getSettingValue('microsoft_client_secret') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="microsoft_tentant_id">Microsoft Tenant Id</label>
                                        <input type="text" name="microsoft_tentant_id" class="form-control" id="microsoft_tentant_id" placeholder="Enter Microsoft Tenant Id" value="{{ getSettingValue('microsoft_tentant_id') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="microsoft_redirect_uri">Microsot Redirect URI</label>
                                        <input type="text" name="microsoft_redirect_uri" class="form-control" id="microsoft_redirect_uri" placeholder="Enter Microsot Redirect URI" value="{{ getSettingValue('microsoft_redirect_uri') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#zoomsetting').validate({
            rules: {
                zoom_api_url: {
                    required: true,
                },
                zoom_api_key: {
                    required: true
                },
                zoom_api_secret: {
                    required: true
                },
                zoom_api_jwt: {
                    required: true
                },
            },
            messages: {
                zoom_api_url: "Please enter zoom api url",
                zoom_api_key: "Please enter zoom api key",
                zoom_api_secret: "Please enter zoom api secret",
                zoom_api_jwt: "Please enter zoom api jwt",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    $(function() {
        $('#teamsetting').validate({
            rules: {
                microsoft_client_id: {
                    required: true,
                },
                microsoft_client_secret: {
                    required: true
                },
                microsoft_tentant_id: {
                    required: true
                },
                microsoft_redirect_id: {
                    required: true
                },
            },
            messages: {
                microsoft_client_id: "Please enter zoom api url",
                microsoft_client_secret: "Please enter zoom api key",
                microsoft_tentant_id: "Please enter zoom api secret",
                microsoft_redirect_id: "Please enter zoom api jwt",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection