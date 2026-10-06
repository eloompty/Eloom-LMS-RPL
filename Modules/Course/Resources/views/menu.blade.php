@extends('user::layouts.master')
@section('title', 'Admin | Courses Menu')

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
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.course.index') }}">
                                        <button class="btn btn-outline-primary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 56 56">
                                                <path fill="currentColor" d="M16.293 29.77c6.539 0 11.906-5.368 11.906-11.93c0-6.516-5.367-11.906-11.906-11.906c-6.516 0-11.906 5.39-11.906 11.906c0 6.562 5.39 11.93 11.906 11.93M33.8 13.246h16.008c1.008 0 1.804-.773 1.804-1.781c0-.985-.797-1.758-1.804-1.758H33.8c-1.008 0-1.782.773-1.782 1.758c0 1.008.774 1.781 1.782 1.781M14.887 24.824a1.64 1.64 0 0 1-1.149-.492l-4.5-4.922c-.164-.187-.281-.61-.281-.914c0-.82.633-1.453 1.43-1.453c.492 0 .843.234 1.101.492l3.328 3.633l6.211-8.625c.258-.375.68-.633 1.196-.633c.773 0 1.453.61 1.453 1.43c0 .234-.117.562-.328.844l-7.266 10.101c-.234.328-.703.54-1.195.54m18.914.703h16.008c1.008 0 1.804-.773 1.804-1.78c0-.985-.797-1.759-1.804-1.759H33.8c-1.008 0-1.782.774-1.782 1.758c0 1.008.774 1.781 1.782 1.781M6.168 37.81h43.64a1.786 1.786 0 0 0 1.805-1.782c0-.984-.797-1.758-1.804-1.758H6.168c-1.008 0-1.781.774-1.781 1.758c0 .985.773 1.782 1.78 1.782m0 12.257h43.64c1.008 0 1.805-.773 1.805-1.757c0-.985-.797-1.782-1.804-1.782H6.168a1.766 1.766 0 0 0-1.781 1.782c0 .984.773 1.757 1.78 1.757" />
                                            </svg>
                                            Registered
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.unregistered.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 56 56">
                                                <path fill="currentColor" d="M16.293 29.77c6.539 0 11.906-5.368 11.906-11.93c0-6.516-5.367-11.906-11.906-11.906c-6.516 0-11.906 5.39-11.906 11.906c0 6.562 5.39 11.93 11.906 11.93M33.8 13.246h16.008c1.008 0 1.804-.773 1.804-1.781c0-.985-.797-1.758-1.804-1.758H33.8c-1.008 0-1.782.773-1.782 1.758c0 1.008.774 1.781 1.782 1.781M12.848 23.395c-.61.585-1.477.468-2.016-.07c-.562-.54-.656-1.43-.07-2.016l3.539-3.54l-3.258-3.28c-.516-.54-.516-1.407 0-1.9a1.365 1.365 0 0 1 1.922 0l3.281 3.235l3.516-3.515c.586-.586 1.476-.47 2.015.07c.54.539.656 1.406.07 2.016L18.31 17.91l3.257 3.281c.516.54.516 1.43 0 1.922a1.41 1.41 0 0 1-1.921 0l-3.258-3.258ZM33.8 25.527h16.008c1.008 0 1.804-.773 1.804-1.78c0-.985-.797-1.759-1.804-1.759H33.8c-1.008 0-1.782.774-1.782 1.758c0 1.008.774 1.781 1.782 1.781M6.168 37.81h43.64a1.786 1.786 0 0 0 1.805-1.782c0-.984-.797-1.758-1.804-1.758H6.168c-1.008 0-1.781.774-1.781 1.758c0 .985.773 1.782 1.78 1.782m0 12.257h43.64c1.008 0 1.805-.773 1.805-1.757c0-.985-.797-1.782-1.804-1.782H6.168a1.766 1.766 0 0 0-1.781 1.782c0 .984.773 1.757 1.78 1.757" />
                                            </svg>
                                            Unregistered
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        
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