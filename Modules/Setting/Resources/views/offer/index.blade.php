@extends('user::layouts.master')
@section('title', 'Admin | Offer Settings')

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
                <h1>Offer Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Offer Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="updatesetting" action="{{ route('admin.setting.offer.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Offer Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_signed_by_name">Offer Signed By Name</label>
                                        <input type="text" name="offer_signed_by_name" class="form-control" id="offer_signed_by_name" placeholder="Enter Offer Signed By Name" value="{{ getSettingValue('offer_signed_by_name') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_signed_by_designation">Offer Signed By Designation</label>
                                        <input type="text" name="offer_signed_by_designation" class="form-control" id="offer_signed_by_designation" placeholder="Enter Offer Signed By Designation" value="{{ getSettingValue('offer_signed_by_designation') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_signature">Offer Signature</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="offer_signature" class="custom-file-input" id="offer_signature" onchange="readURL(this);">
                                                <label class="custom-file-label" for="offer_signature">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if (getSettingValue('offer_signature'))
                                        <img src="{{ asset(getSettingValue('offer_signature')) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @else
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_college_logo">Offer College Logo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="offer_college_logo" class="custom-file-input" id="offer_college_logo" onchange="readCollegeURL(this);">
                                                <label class="custom-file-label" for="offer_college_logo">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if (getSettingValue('offer_college_logo'))
                                        <img src="{{ asset(getSettingValue('offer_college_logo')) }}" id="college-box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @else
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="college-box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_payment_details_onshore">Payment Details (Onshore Students)</label>
                                        <textarea name="offer_payment_details_onshore" class="form-control" id="offer_payment_details_onshore" rows="6" placeholder="Bank, account name, BSB / SWIFT, account number, bank address (one per line)">{{ getSettingValue('offer_payment_details_onshore') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_payment_details_offshore">Payment Details (Offshore Students)</label>
                                        <textarea name="offer_payment_details_offshore" class="form-control" id="offer_payment_details_offshore" rows="6" placeholder="Bank, account name, BSB / SWIFT, account number, bank address (one per line)">{{ getSettingValue('offer_payment_details_offshore') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="offer_terms_and_conditions">Terms and Conditions of Enrolment</label>
                                        <textarea name="offer_terms_and_conditions" class="form-control" id="offer_terms_and_conditions" rows="12" placeholder="Printed on the offer letter above the student declaration. Leave empty to use a short default that refers students to your policies.">{{ getSettingValue('offer_terms_and_conditions') }}</textarea>
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
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </form>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readCollegeURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#college-box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection