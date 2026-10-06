@extends('user::layouts.master')
@section('title', 'Admin | AVETMISS Report')

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
                <h1>AVETMISS Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">AVETMISS Report</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form id="export" method="POST" action="{{ route('admin.setting.export.type') }}">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Export</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="from">From</label> <span class="required">*</span>
                                    <input type="date" name="from" class="form-control" id="from">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="to">To</label> <span class="required">*</span>
                                    <input type="date" name="to" class="form-control" id="to">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="type">Export Type</label> <span class="required">*</span>
                                    <select name="export_type" class="form-control" id="export_type">
                                        <option value="" disabled>-- Select Export Type --</option>
                                        <option value="csv">CSV</option>
                                        <option value="txt" selected>TXT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </form>
        <!-- /.row -->
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
        $('#export').validate({
            rules: {
                from: {
                    required: true,
                },
                to: {
                    required: true,
                },
                export_type: {
                    required: true,
                },
            },
            messages: {
                from: "Please choose one from date",
                to: "Please choose one to date",
                export_type: "Please choose one export size",
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