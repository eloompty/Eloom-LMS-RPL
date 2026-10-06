@extends('user::layouts.master')
@section('title', 'Admin | Company Menu')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Courses</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Courses Menu</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- ./row -->
        <div class="row">

            <!-- /.col -->
            <div class="col-md-12">
                <!-- Application buttons -->
                <div class="card">
                    <!-- <div class="card-header">
                <h3 class="card-title">Application Buttons</h3>
              </div> -->
                    <div class="my_btn_list">
                        @if (checkRole('company', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.company') }}">
                                        <button class="btn btn-outline-primary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                            </svg>
                                            Company
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('company_delivery_site', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.company.delivery.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M19 2H9c-1.103 0-2 .897-2 2v5.586l-4.707 4.707A1 1 0 0 0 3 16v5a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4c0-1.103-.897-2-2-2m-8 18H5v-5.586l3-3l3 3zm8 0h-6v-4a.999.999 0 0 0 .707-1.707L9 9.586V4h10z" />
                                                <path fill="currentColor" d="M11 6h2v2h-2zm4 0h2v2h-2zm0 4.031h2V12h-2zM15 14h2v2h-2zm-8 1h2v2H7z" />
                                            </svg>
                                            Company Delivery Sites
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /. row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection