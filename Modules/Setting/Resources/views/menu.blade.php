@extends('user::layouts.master')
@section('title', 'Admin | Settings Menu')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Settings Menu</li>
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
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.setting.index') }}">
                                        <button class="btn btn-outline-primary new_btn_app">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M19.9 12.66a1 1 0 0 1 0-1.32l1.28-1.44a1 1 0 0 0 .12-1.17l-2-3.46a1 1 0 0 0-1.07-.48l-1.88.38a1 1 0 0 1-1.15-.66l-.61-1.83a1 1 0 0 0-.95-.68h-4a1 1 0 0 0-1 .68l-.56 1.83a1 1 0 0 1-1.15.66L5 4.79a1 1 0 0 0-1 .48L2 8.73a1 1 0 0 0 .1 1.17l1.27 1.44a1 1 0 0 1 0 1.32L2.1 14.1a1 1 0 0 0-.1 1.17l2 3.46a1 1 0 0 0 1.07.48l1.88-.38a1 1 0 0 1 1.15.66l.61 1.83a1 1 0 0 0 1 .68h4a1 1 0 0 0 .95-.68l.61-1.83a1 1 0 0 1 1.15-.66l1.88.38a1 1 0 0 0 1.07-.48l2-3.46a1 1 0 0 0-.12-1.17ZM18.41 14l.8.9l-1.28 2.22l-1.18-.24a3 3 0 0 0-3.45 2L12.92 20h-2.56L10 18.86a3 3 0 0 0-3.45-2l-1.18.24l-1.3-2.21l.8-.9a3 3 0 0 0 0-4l-.8-.9l1.28-2.2l1.18.24a3 3 0 0 0 3.45-2L10.36 4h2.56l.38 1.14a3 3 0 0 0 3.45 2l1.18-.24l1.28 2.22l-.8.9a3 3 0 0 0 0 3.98m-6.77-6a4 4 0 1 0 4 4a4 4 0 0 0-4-4m0 6a2 2 0 1 1 2-2a2 2 0 0 1-2 2" />
                                            </svg>
                                            Settings
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.setting.onlineclass.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                                                <path fill="currentColor" d="M6.001 7.5a4.5 4.5 0 0 1 4.5-4.5h15a4.5 4.5 0 0 1 4.5 4.5v10a4.5 4.5 0 0 1-4.5 4.5h-6.309c-.6-1.316-1.876-2.17-3.192-2.422V18a3 3 0 0 1 3-3h5a3 3 0 0 1 3 3v1.5a2.496 2.496 0 0 0 1.001-2v-10a2.5 2.5 0 0 0-2.5-2.5h-15a2.5 2.5 0 0 0-2.5 2.5v.314a6.483 6.483 0 0 0-2 1.062zm9.19 13.5a2.99 2.99 0 0 1 2.224 1h-.001a2.55 2.55 0 0 1 .627 1.873c-.135 2.074-.918 3.68-2.403 4.728C14.205 29.612 12.26 30 9.999 30c-2.248 0-4.156-.384-5.566-1.386c-1.458-1.037-2.228-2.619-2.417-4.65C1.853 22.218 3.35 21 4.872 21zm6.309-7a3.5 3.5 0 1 0 0-7a3.5 3.5 0 0 0 0 7M15 14a5 5 0 1 1-10 0a5 5 0 0 1 10 0" />
                                            </svg>
                                            Online Class Settings
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.setting.notification.index') }}">
                                        <button class="btn btn-outline-warning new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M22.72 19.5a4.193 4.193 0 0 0 0-1l1.05-.82c.1-.07.12-.18.06-.32l-1-1.72c-.06-.11-.19-.14-.3-.11l-1.25.47c-.28-.17-.53-.34-.84-.46l-.19-1.33A.249.249 0 0 0 20 14h-2c-.12 0-.23.09-.25.21l-.18 1.33c-.32.12-.57.29-.85.46l-1.22-.47c-.13-.03-.27 0-.33.11l-1 1.72c-.06.14-.03.25.06.32l1.06.82c-.02.17-.04.34-.04.5s.02.33.04.5l-1.06.82c-.09.07-.12.21-.06.32l1 1.73c.06.13.2.13.33.13l1.22-.53c.28.2.53.37.85.5l.18 1.32c.02.12.13.21.25.21h2c.13 0 .23-.09.25-.21l.19-1.32c.31-.13.56-.3.84-.5l1.25.53c.11 0 .24 0 .3-.13l1-1.73c.06-.11.04-.25-.06-.32zM19 20.75c-.96 0-1.75-.78-1.75-1.75s.79-1.75 1.75-1.75s1.75.78 1.75 1.75s-.78 1.75-1.75 1.75M12.08 20H3v-1l2-2v-6c0-3.1 2-5.8 5-6.7V4c0-1.1.9-2 2-2s2 .9 2 2v.3c3 .9 5 3.6 5 6.7v1c-.69 0-1.37.11-2 .29V11c0-2.8-2.2-5-5-5s-5 2.2-5 5v7h5.08c-.05.33-.08.66-.08 1c0 .34.03.67.08 1m.22 1c.2.6.44 1.17.76 1.69c-.31.19-.67.31-1.06.31c-1.1 0-2-.9-2-2z" />
                                            </svg>
                                            Notification Settings
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.setting.assignment.index') }}">
                                        <button class="btn btn-outline-danger new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                                                <path fill="currentColor" d="M5 5h5.999V4H5zM3 5h1V4H3zm0 3h1V7H3zm6.022-1l-.15.333l-.737-.078l-.467-.05l-.33.342a5.13 5.13 0 0 0-.39.453H5V7zm-3.005 3L6 10.056l.306.411l.399.533H5v-1zM3 11h1v-1H3z" />
                                                <path fill="currentColor" d="m13 7.05l-.162-.359l-.2-.447l-.47-.11A5.019 5.019 0 0 0 12 6.098V2H2v11h4.36c.157.354.355.69.59 1H1V1h12z" />
                                                <path fill="currentColor" d="M11.004 7c.322 0 .646.036.966.109l.595 1.293l1.465-.152c.457.462.786 1.016.969 1.61l-.87 1.14l.871 1.141a3.94 3.94 0 0 1-.387.859a4.058 4.058 0 0 1-.583.75l-1.465-.152l-.594 1.292a4.37 4.37 0 0 1-1.941.001l-.594-1.293l-1.466.152a3.954 3.954 0 0 1-.969-1.61l.87-1.14L7 9.86a3.947 3.947 0 0 1 .97-1.61l1.466.152l.593-1.292a4.37 4.37 0 0 1 .975-.11M11 12a1 1 0 1 0 .002-1.998A1 1 0 0 0 11 12" />
                                            </svg>
                                            Assignment Settings
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.attendance.index') }}">
                                <div class="inner_grid">

                                    <button class="btn btn-outline-danger new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48">
                                            <circle cx="11.425" cy="13.057" r="5.925" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                            <circle cx="11.425" cy="25.753" r="2.902" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                            <circle cx="11.425" cy="37.966" r="2.902" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M18.922 37.966H42.5M18.922 25.753H42.5M21.803 13.057H42.5m-33.906.234l1.732 1.731l3.929-3.93" />
                                        </svg>
                                        Attendance Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.email.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-secondary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M3 4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10.5a6.5 6.5 0 0 1-.5-2H3V8l8 5l8-5v3a7 7 0 0 1 .5 0a6.5 6.5 0 0 1 1.5.18V6c0-1.1-.9-2-2-2zm0 2h16l-8 5zm16 6l-2.25 2.25L19 16.5V15a2.5 2.5 0 0 1 2.5 2.5c0 .4-.09.78-.26 1.12l1.09 1.09c.42-.63.67-1.39.67-2.21c0-2.21-1.79-4-4-4zm-3.33 3.29c-.42.63-.67 1.39-.67 2.21c0 2.21 1.79 4 4 4V23l2.25-2.25L19 18.5V20a2.5 2.5 0 0 1-2.5-2.5c0-.4.09-.78.26-1.12z" />
                                        </svg>
                                        Email Setting
                                    </button>
                                </div>
                            </a>
                        </div>

                        @endif
                        @if (checkRole('user', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.user.index') }}">
                                        <button class="btn btn-outline-focus new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M11.5 4a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7M6 7.5a5.5 5.5 0 1 1 11 0a5.5 5.5 0 0 1-11 0M8 16a4 4 0 0 0-4 4h8.05v2H2v-2a6 6 0 0 1 6-6h4v2zm11.5-3.25v1.376c.715.184 1.352.56 1.854 1.072l1.193-.689l1 1.732l-1.192.688a4.008 4.008 0 0 1 0 2.142l1.192.688l-1 1.732l-1.193-.689a4 4 0 0 1-1.854 1.072v1.376h-2v-1.376a3.996 3.996 0 0 1-1.854-1.072l-1.193.689l-1-1.732l1.192-.688a4.004 4.004 0 0 1 0-2.142l-1.192-.688l1-1.732l1.193.688a3.996 3.996 0 0 1 1.854-1.071V12.75zm-2.751 4.283a1.991 1.991 0 0 0-.25.967c0 .35.091.68.25.967l.036.063a1.999 1.999 0 0 0 3.43 0l.036-.063c.159-.287.249-.616.249-.967c0-.35-.09-.68-.249-.967l-.036-.063a1.999 1.999 0 0 0-3.43 0z" />
                                            </svg>
                                            Users
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('role', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.role.index') }}">
                                        <button class="btn btn-outline-warning new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                <path fill="currentColor" d="M6 3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h3.601A5.5 5.5 0 0 1 17 9.601V6a3 3 0 0 0-3-3zm3.354 3.396a.5.5 0 0 1 0 .708l-1.75 1.75a.5.5 0 0 1-.691.015l-.75-.685a.5.5 0 1 1 .674-.738l.397.362l1.412-1.412a.5.5 0 0 1 .708 0m-.708 5a.5.5 0 0 1 .708.708l-1.75 1.75a.5.5 0 0 1-.691.015l-.75-.685a.5.5 0 0 1 .674-.738l.397.363zM11 8a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 0 1zm-.366 5.92a2 2 0 0 0 1.43-2.478l-.156-.557c.255-.197.53-.364.822-.5l.337.358a2 2 0 0 0 2.91 0l.322-.343c.298.14.578.313.835.518l-.126.422a2.001 2.001 0 0 0 1.456 2.519l.35.082a4.595 4.595 0 0 1 .01 1.017l-.46.118a1.998 1.998 0 0 0-1.432 2.478l.156.556c-.254.197-.53.365-.822.5l-.337-.358a1.999 1.999 0 0 0-2.909 0l-.32.348a4.355 4.355 0 0 1-.836-.518l.126-.423a2 2 0 0 0-1.456-2.52l-.349-.082a4.622 4.622 0 0 1-.01-1.016zm4.865.58a1 1 0 1 0-2 0a1 1 0 0 0 2 0" />
                                            </svg>
                                            Roles
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.identifier.index') }}">
                                        <button class="btn btn-outline-primary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5m6-10.125a1.875 1.875 0 1 1-3.75 0a1.875 1.875 0 0 1 3.75 0m1.294 6.336a6.721 6.721 0 0 1-3.17.789a6.721 6.721 0 0 1-3.168-.789a3.376 3.376 0 0 1 6.338 0" />
                                            </svg>
                                            Identifiers
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.identifier.type.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5m6-10.125a1.875 1.875 0 1 1-3.75 0a1.875 1.875 0 0 1 3.75 0m1.294 6.336a6.721 6.721 0 0 1-3.17.789a6.721 6.721 0 0 1-3.168-.789a3.376 3.376 0 0 1 6.338 0" />
                                            </svg>

                                            Identifier Types
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.identifier.country.index') }}">
                                        <button class="btn btn-outline-secondary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 36 36">
                                                <path fill="currentColor" d="M10.85 27.44a2.29 2.29 0 0 0 1.74-1.68c.54-.14 1.06-.32 1.59-.51v-1.2a2.77 2.77 0 0 1 .06-.51a17.44 17.44 0 0 1-1.82.62a2.28 2.28 0 0 0-4.28.63h-.45a11.93 11.93 0 0 1-2.88-7.27a17.79 17.79 0 0 1 5-4.72a2.23 2.23 0 0 0 2.29.56a18.52 18.52 0 0 0 4.47 5a2.74 2.74 0 0 1 .21-.24l.95-.91a16.9 16.9 0 0 1-4.35-4.79a2.27 2.27 0 0 0 .35-1.2V11A17.69 17.69 0 0 1 25 11a17.49 17.49 0 0 1-1.15 3.34h1.75a19 19 0 0 0 .91-2.72c.43.19.84.41 1.26.64a11.94 11.94 0 0 1 1 4.09A2.77 2.77 0 0 1 30 16a2.73 2.73 0 0 1 .68.1A14 14 0 1 0 16.08 31a2.72 2.72 0 0 1 0-2a11.93 11.93 0 0 1-5.23-1.56M16.76 5a12 12 0 0 1 8.61 3.66c0 .25 0 .51-.08.76a19.21 19.21 0 0 0-12.35.11a2.28 2.28 0 0 0-1.2-.53a17 17 0 0 1-.61-2.53A11.92 11.92 0 0 1 16.76 5m-7.1 2.36a18.72 18.72 0 0 0 .49 1.92a2.28 2.28 0 0 0-1.07 1.93s0 .1 0 .15A19.45 19.45 0 0 0 5 14.79a12 12 0 0 1 4.66-7.43" class="clr-i-outline clr-i-outline-path-1" />
                                                <path fill="currentColor" d="M25 21.19A3.84 3.84 0 1 0 28.88 25A3.87 3.87 0 0 0 25 21.19m0 6.08A2.24 2.24 0 1 1 27.28 25A2.26 2.26 0 0 1 25 27.27" class="clr-i-outline clr-i-outline-path-2" />
                                                <path fill="currentColor" d="M34.17 24.14a1.14 1.14 0 0 0-.7-1.1l-1.56-.46q-.11-.32-.26-.63l.72-1.33a1.14 1.14 0 0 0-.21-1.34l-1.34-1.32a1.14 1.14 0 0 0-1.34-.2l-1.34.71a7.28 7.28 0 0 0-.67-.28L27 16.71a1.14 1.14 0 0 0-1.08-.76H24a1.14 1.14 0 0 0-1.08.8l-.44 1.43a7.32 7.32 0 0 0-.68.28l-1.32-.7a1.14 1.14 0 0 0-1.33.19l-1.37 1.31a1.14 1.14 0 0 0-.21 1.35l.7 1.28q-.16.32-.28.65l-1.41.46a1.13 1.13 0 0 0-.81 1.09v1.87a1.14 1.14 0 0 0 .82 1.04l1.47.44q.12.32.28.64l-.72 1.35a1.14 1.14 0 0 0 .2 1.35l1.34 1.32a1.14 1.14 0 0 0 1.34.2l1.37-.72q.31.14.63.26l.44 1.47a1.14 1.14 0 0 0 1.09.8h1.9a1.14 1.14 0 0 0 1.07-.8l.44-1.47c.21-.07.42-.16.62-.25l1.38.73a1.14 1.14 0 0 0 1.33-.2l1.34-1.32a1.14 1.14 0 0 0 .21-1.35l-.73-1.34q.14-.3.25-.6l1.5-.44a1.13 1.13 0 0 0 .83-1.07Zm-1.6 1.5l-2 .58l-.12.42a5.55 5.55 0 0 1-.45 1.09l-.21.38l1 1.79l-.86.84l-1.82-1l-.37.2a5.78 5.78 0 0 1-1.12.46l-.42.12l-.59 2h-1.23l-.59-1.95l-.42-.12a5.86 5.86 0 0 1-1.13-.45l-.37-.2l-1.81 1l-.86-.85l1-1.82l-.22-.38a5.6 5.6 0 0 1-.49-1.13l-.13-.41l-1.95-.58v-1.21l1.94-.58l.12-.41a5.53 5.53 0 0 1 .49-1.14l.22-.39l-1-1.73l.87-.84l1.77.94l.38-.21a5.8 5.8 0 0 1 1.17-.49l.41-.12l.59-1.91h1.23l.58 1.9l.41.12a5.79 5.79 0 0 1 1.16.48l.38.21l1.8-.95l.86.85l-1 1.77l.21.38a5.53 5.53 0 0 1 .47 1.13l.12.42l1.93.57Z" class="clr-i-outline clr-i-outline-path-3" />
                                                <path fill="none" d="M0 0h36v36H0z" />
                                            </svg>
                                            Country Identifiers
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('database_backup', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.backup.index') }}">
                                        <button class="btn btn-outline-primary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                                                    <path d="M4 6v6s0 3 7 3s7-3 7-3V6" />
                                                    <path d="M11 3c7 0 7 3 7 3s0 3-7 3s-7-3-7-3s0-3 7-3m0 18c-7 0-7-3-7-3v-6m15 9a2 2 0 1 0 0-4a2 2 0 0 0 0 4" />
                                                    <path stroke-dasharray=".3 2" d="M19 22a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                                                </g>
                                            </svg>
                                            Database Backup
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.setting.export.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="m18.075 17.5l-1.425 1.45q-.15.15-.15.35t.15.35q.15.15.35.15t.35-.15l1.95-1.95q.3-.3.3-.7t-.3-.7l-1.95-1.95q-.15-.15-.35-.15t-.35.15q-.15.15-.15.35t.15.35l1.45 1.45h-3.6q-.2 0-.35.15T14 17q0 .2.15.35t.35.15zM17 22q-2.075 0-3.537-1.463T12 17q0-2.075 1.463-3.537T17 12q2.075 0 3.538 1.463T22 17q0 2.075-1.463 3.538T17 22m-6.425 0q-.675 0-1.037-.45t-.463-1.1L8.85 18.8q-.325-.125-.612-.3t-.563-.375l-1.55.65q-.625.275-1.25.05t-.975-.8l-1.175-2.05q-.35-.575-.2-1.225t.675-1.075l1.325-1Q4.5 12.5 4.5 12.337v-.675q0-.162.025-.337l-1.325-1Q2.675 9.9 2.525 9.25t.2-1.225L3.9 5.975q.35-.575.975-.8t1.25.05l1.55.65q.275-.2.575-.375t.6-.3l.225-1.65q.1-.65.588-1.1T10.825 2h2.35q.675 0 1.163.45t.587 1.1l.225 1.65q.325.125.613.3t.562.375l1.55-.65q.625-.275 1.25-.05t.975.8l1.175 2.05q.35.575.2 1.225t-.675 1.075l-.6.45q-.325.275-.725.212T18.8 10.6q-.275-.325-.225-.725t.375-.675l.475-.35l-.975-1.7l-2.475 1.05q-.55-.575-1.213-.962t-1.437-.588L13 4h-1.975l-.35 2.65q-.775.2-1.437.588t-1.213.937L5.55 7.15l-.975 1.7l2.15 1.6q-.125.375-.175.75t-.05.8q0 .4.05.775t.175.75l-2.15 1.625l.975 1.7l2.475-1.05q.425.425.913.763t1.062.562q.025 1.1.363 2.088t.912 1.812q.2.3-.025.638t-.675.337M12.05 8.5q-1.45 0-2.475 1.013T8.55 12q0 .525.15 1.025t.45.95q.275.375.713.475t.787-.175q.35-.25.413-.663t-.213-.712q-.15-.2-.225-.413T10.55 12q0-.625.438-1.062t1.062-.438q.25 0 .488.088t.437.237q.3.225.675.163t.625-.413q.25-.35.163-.775T14 9.1q-.35-.3-.85-.45t-1.1-.15" />
                                            </svg>
                                            AVETMISS Export
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('avetmiss_backup', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.avetmiss.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M14 12c0-1.1-.9-2-2-2s-2 .9-2 2s.9 2 2 2s2-.9 2-2m-2-9a9 9 0 0 0-9 9H0l4 4l4-4H5c0-3.87 3.13-7 7-7s7 3.13 7 7a6.995 6.995 0 0 1-11.06 5.7l-1.42 1.44A9 9 0 1 0 12 3" />
                                        </svg> AVETMISS Backup
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('template', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.certificate.template.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256">
                                            <path fill="currentColor" d="M128 136a8 8 0 0 1-8 8H72a8 8 0 0 1 0-16h48a8 8 0 0 1 8 8m-8-40H72a8 8 0 0 0 0 16h48a8 8 0 0 0 0-16m112 65.47V224a8 8 0 0 1-12 7l-24-13.74L172 231a8 8 0 0 1-12-7v-24H40a16 16 0 0 1-16-16V56a16 16 0 0 1 16-16h176a16 16 0 0 1 16 16v30.53a51.88 51.88 0 0 1 0 74.94M160 184v-22.53A52 52 0 0 1 216 76V56H40v128Zm56-12a51.88 51.88 0 0 1-40 0v38.22l16-9.16a8 8 0 0 1 7.94 0l16 9.16Zm16-48a36 36 0 1 0-36 36a36 36 0 0 0 36-36" />
                                        </svg>
                                        Certificate Templates
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('email', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.email.index') }}">
                                        <button class="btn btn-outline-secondary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                                            </svg>
                                            Emails
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('email_template', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.email.template.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M22 5.5H9c-1.1 0-2 .9-2 2v9a2 2 0 0 0 2 2h13c1.11 0 2-.89 2-2v-9a2 2 0 0 0-2-2m0 11H9V9.17l6.5 3.33L22 9.17zm-6.5-5.69L9 7.5h13zM5 16.5c0 .17.03.33.05.5H1c-.552 0-1-.45-1-1s.448-1 1-1h4zM3 7h2.05c-.02.17-.05.33-.05.5V9H3c-.55 0-1-.45-1-1s.45-1 1-1m-2 5c0-.55.45-1 1-1h3v2H2c-.55 0-1-.45-1-1" />
                                            </svg>
                                            Email Templates
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('email_user', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.email.user.index') }}">
                                        <button class="btn btn-outline-warning new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M13 19c0-.34.04-.67.09-1H4V8l8 5l8-5v5.09c.72.12 1.39.37 2 .72V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h9.09c-.05-.33-.09-.66-.09-1m7-13l-8 5l-8-5zm0 16v-2h-4v-2h4v-2l3 3z" />
                                            </svg>
                                            Send Emails
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('document_type', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.document.type.index') }}">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 2048 2048">
                                                <path fill="currentColor" d="M1103 1920q23 37 52 68t62 60H128V0h1115l549 549v494q-63-22-128-29V640h-512V128H256v1792zm177-1701v293h293zm-128 998q-13 15-25 30t-24 33H512v-128h640zm-640 319v-128h512v60q0 14-4 33t-6 35zm896-640v128H512V896zm512 704q0 31-6 61l124 51l-49 119l-124-52q-35 51-86 86l52 124l-119 49l-51-124q-30 6-61 6t-61-6l-51 124l-119-49l52-124q-51-35-86-86l-124 52l-49-119l124-51q-6-30-6-61t6-61l-124-51l49-119l124 52q18-25 39-47t47-39l-52-124l119-49l51 124q30-6 61-6t61 6l51-124l119 49l-52 124q51 35 86 86l124-52l49 119l-124 51q6 30 6 61m-128 0q0-40-15-75t-41-61t-61-41t-75-15t-75 15t-61 41t-41 61t-15 75t15 75t41 61t61 41t75 15t75-15t61-41t41-61t15-75" />
                                            </svg>
                                            Document Types
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('document_type', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items">
                                <div class="inner_grid">
                                    <a href="{{ route('admin.document.type.student.index') }}">
                                        <button class="btn btn-outline-primary new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M18.25 3A2.75 2.75 0 0 1 21 5.75v6.272a6.5 6.5 0 0 0-1.5-.709V5.75c0-.69-.56-1.25-1.25-1.25H5.75c-.69 0-1.25.56-1.25 1.25v12.5c0 .69.56 1.25 1.25 1.25h5.563c.173.534.412 1.037.709 1.5H5.75A2.75 2.75 0 0 1 3 18.25V5.75A2.75 2.75 0 0 1 5.75 3zm-4 8.5c.162 0 .313.052.435.14A6.5 6.5 0 0 0 12.81 13H6.75a.75.75 0 0 1-.102-1.493l.102-.007zm-7.5 4h4.563c-.154.478-.255.98-.294 1.5H6.75a.75.75 0 0 1-.102-1.493zm10.5-8H6.75l-.102.007A.75.75 0 0 0 6.75 9h10.5l.102-.007A.75.75 0 0 0 17.25 7.5m-4.75 8.129l.447.43a2 2 0 0 1 0 2.882l-.447.43c.2.574.49 1.103.853 1.57l.602-.178a2 2 0 0 1 2.51 1.45l.174.715a5.2 5.2 0 0 0 1.722 0l.173-.716a2 2 0 0 1 2.511-1.449l.602.178c.362-.467.652-.996.853-1.57l-.447-.43a2 2 0 0 1 0-2.882l.447-.43a5.5 5.5 0 0 0-.853-1.57l-.602.178a2 2 0 0 1-2.51-1.45l-.174-.715a5.2 5.2 0 0 0-1.723 0l-.172.716a2 2 0 0 1-2.511 1.449l-.602-.178a5.5 5.5 0 0 0-.853 1.57m5 3.371c-.8 0-1.45-.672-1.45-1.5S16.7 16 17.5 16s1.45.672 1.45 1.5S18.3 19 17.5 19" />
                                            </svg> Student Document Types
                                        </button>
                                    </a>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('lead', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.lead.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-dark new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor">
                                                <path d="M11 5h7m-8 5l4.5 4.5M5 11v7" />
                                                <circle cx="6.444" cy="6.444" r="4.444" />
                                                <circle cx="5" cy="20" r="2" />
                                                <circle cx="16" cy="16" r="2" />
                                                <circle cx="20" cy="5" r="2" />
                                            </g>
                                        </svg>
                                        CRM
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('zoho_lead', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.zoho.index') }}">
                                <div class="inner_grid">

                                    <button class="btn btn-outline-info new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="2.9em" height="1em" viewBox="0 0 512 177">
                                            <defs>
                                                <linearGradient id="logosZoho0" x1="49.996%" x2="49.996%" y1="1.431%" y2="96.981%">
                                                    <stop offset=".562%" stop-color="#ffe513" />
                                                    <stop offset="100%" stop-color="#fdb924" />
                                                </linearGradient>
                                                <linearGradient id="logosZoho1" x1="4.512%" x2="95.672%" y1="13.494%" y2="87.064%">
                                                    <stop offset=".562%" stop-color="#008cd2" />
                                                    <stop offset="100%" stop-color="#00649d" />
                                                </linearGradient>
                                                <linearGradient id="logosZoho2" x1="50.002%" x2="50.002%" y1="27.779%" y2="97.529%">
                                                    <stop offset="0%" stop-color="#26a146" />
                                                    <stop offset="100%" stop-color="#008a52" />
                                                </linearGradient>
                                                <linearGradient id="logosZoho3" x1="43.734%" x2="57.544%" y1="8.208%" y2="93.198%">
                                                    <stop offset="0%" stop-color="#d92231" />
                                                    <stop offset="100%" stop-color="#ba2234" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="#e79225" d="M512 37.7v121.4l-16.8 16.4V56.7z" />
                                            <path fill="#fff16d" d="m401.9 37.8l-17.4 18.9l.9 1.2l109.1-.4l1-.8l16.5-19z" />
                                            <path fill="url(#logosZoho0)" d="M.5 19.7h111v118.9H.5z" transform="translate(384 37)" />
                                            <path fill="#fff" d="M478.6 99.5c-2.2-5.5-5.5-10.5-9.8-14.8c-4.1-4.2-8.7-7.4-13.9-9.5c-5.1-2.1-10.6-3.2-16.6-3.2s-11.6 1.1-16.7 3.2c-5.2 2.1-9.8 5.3-13.9 9.5c-4.3 4.3-7.5 9.3-9.7 14.8s-3.2 11.5-3.2 18.1q0 9.6 3.3 18c2.2 5.6 5.4 10.6 9.7 15c4 4.1 8.6 7.2 13.7 9.3s10.8 3.2 16.9 3.2c5.9 0 11.4-1.1 16.5-3.2s9.8-5.2 13.9-9.3c4.3-4.4 7.6-9.4 9.8-14.9s3.3-11.6 3.3-18c0-6.7-1.1-12.7-3.3-18.2m-22.9 39.2c-4.3 5.1-10 7.7-17.4 7.7s-13.2-2.6-17.5-7.7s-6.4-12.2-6.4-21.2c0-9.2 2.2-16.3 6.4-21.5c4.3-5.2 10-7.7 17.5-7.7c7.4 0 13.1 2.6 17.4 7.7c4.2 5.2 6.4 12.3 6.4 21.5c0 9-2.1 16.1-6.4 21.2" />
                                            <path fill="#009ada" d="M373.6 27.8v.6l14.2 109.1l-8.3 23l-1.1-.8l-14.6-104.6l.3-1.4l9.1-25.3z" />
                                            <path fill="#91c9ed" d="m264.3 43l109.3-15.2l-9.2 26.2l-1.3 1.4l-102.2 15l.5-18.7z" />
                                            <path fill="url(#logosZoho1)" d="m107.4 27l15.1 106.5l-107.7 15.1L.3 45.7l6.3-4.9z" transform="translate(257 27)" />
                                            <path fill="#fff" d="M346.1 74.4c-.5-3.3-1.6-5.8-3.4-7.5c-1.5-1.3-3.3-2-5.4-2c-.5 0-1.1 0-1.7.1c-2.8.4-4.9 1.7-6.2 3.8c-1 1.5-1.4 3.4-1.4 5.6c0 .8.1 1.7.2 2.6l3.9 27.7l-31 4.6l-3.9-27.7c-.5-3.2-1.6-5.7-3.4-7.4c-1.5-1.4-3.3-2.1-5.3-2.1c-.5 0-1 0-1.5.1c-2.9.4-5.1 1.7-6.5 3.8c-1 1.5-1.4 3.4-1.4 5.6c0 .8.1 1.7.2 2.7l10.6 72.1c.5 3.3 1.6 5.8 3.6 7.5c1.5 1.3 3.3 1.9 5.5 1.9c.6 0 1.2 0 1.8-.1c2.7-.4 4.7-1.7 6-3.8c.9-1.5 1.3-3.3 1.3-5.4c0-.8-.1-1.7-.2-2.6l-4.3-28.5l31-4.6l4.3 28.5c.5 3.3 1.6 5.8 3.5 7.4c1.5 1.3 3.3 2 5.4 2c.5 0 1.1 0 1.7-.1c2.8-.4 4.9-1.7 6.2-3.8c.9-1.5 1.4-3.3 1.4-5.5c0-.8-.1-1.7-.2-2.6z" />
                                            <path fill="#66bf6b" d="m162 0l-38.9 92.4l5.3 40.6l.3-.1l43.7-98.3l-.2-2.1l-9.4-31.2z" />
                                            <path fill="#98d0a0" d="m162 0l10.1 33.9l.2.7l96.2 43.1l.3-.2l-8.2-32.4z" />
                                            <path fill="url(#logosZoho2)" d="m49.1 33.9l96.7 43.6l-43.7 99.1L5.4 133z" transform="translate(123)" />
                                            <path fill="#fff" d="M239.5 85.5c-2.1-5.6-5-10.4-8.8-14.4s-8.4-7.2-13.8-9.5s-10.8-3.4-16.3-3.4h-.3c-5.6 0-11.1 1.3-16.5 3.7c-5.7 2.5-10.6 5.9-14.8 10.4c-4.2 4.4-7.6 9.8-10.2 16c-2.6 6.1-4 12.3-4.3 18.4v2.1c0 5.4.9 10.7 2.8 15.9c2 5.5 4.9 10.2 8.7 14.2s8.5 7.2 14.1 9.5c5.3 2.3 10.7 3.4 16.2 3.4h.1c5.5 0 11-1.2 16.4-3.5c5.7-2.5 10.7-6 14.9-10.5c4.2-4.4 7.7-9.7 10.3-15.9s4-12.3 4.3-18.4v-1.8c.1-5.5-.8-10.9-2.8-16.2m-19.3 28.8c-3.6 8.6-8.5 14.5-14.4 17.7c-3.2 1.7-6.5 2.6-9.8 2.6c-2.9 0-6-.7-9.1-2c-6.8-2.9-11-7.5-12.8-14.1q-.9-3.3-.9-6.9c0-4.8 1.2-10.1 3.6-15.8c3.7-8.8 8.6-14.8 14.5-18.1c3.2-1.8 6.5-2.6 9.8-2.6c3 0 6 .7 9.2 2c6.7 2.9 10.9 7.5 12.7 14.1c.6 2.1.9 4.4.9 6.8c0 5-1.2 10.4-3.7 16.3" />
                                            <path fill="#760d16" d="m115.4 15.7l15.8 105.8l-7.2 37.2l-1-1.3l-15.4-102.2v-2l6.8-35.7z" />
                                            <path fill="#ef463e" d="M0 70.4L7.5 33l107.9-17.3l-7.3 38.1v2.5L1.3 71.4z" />
                                            <path fill="url(#logosZoho3)" d="M108.1 38.8L124 143.7L17.2 160.4L0 55.4z" transform="translate(0 15)" />
                                            <path fill="#fff" d="M96.6 142c-.8-1-2-1.7-3.4-2.2s-3.1-.7-5.2-.7c-1.9 0-4.1.2-6.5.6l-28.2 4.8c.3-2.2 1.4-5 3.3-8.5c2.1-3.9 5.3-8.6 9.4-14c1.4-1.9 2.5-3.3 3.3-4.3c.5-.7 1.3-1.6 2.3-2.9c6.5-8.5 10.4-15.4 12-20.8c.9-3.1 1.4-6.2 1.6-9.3c.1-.9.1-1.7.1-2.5q0-3.3-.6-6.6c-.3-2-.8-3.6-1.5-4.9s-1.5-2.3-2.5-2.9c-1.1-.7-2.8-1-4.9-1q-2.55 0-6.3.6L36.9 73c-3.9.7-6.9 1.8-8.7 3.6c-1.5 1.4-2.2 3.2-2.2 5.2c0 .5 0 1.1.1 1.7c.5 2.8 1.9 4.8 4.2 5.8c1.4.6 3 .9 5 .9c1.3 0 2.8-.1 4.4-.4L66.9 85c0 .5.1 1 .1 1.4c0 1.7-.3 3.4-.9 5c-.8 2.3-2.8 5.5-6.1 9.6c-.9 1.1-2.3 2.9-4.2 5.2c-7.4 8.9-12.6 16.5-15.8 22.8c-2.3 4.4-3.8 8.6-4.7 12.9c-.5 2.5-.8 4.8-.8 7.1c0 1.6.1 3.2.4 4.7c.4 2.2.9 4 1.6 5.4s1.7 2.5 2.8 3.1s2.6.8 4.8.8q4.05 0 11.1-1.2l29.6-5.1c5.2-.9 8.9-2.2 11-3.9c1.7-1.4 2.6-3.3 2.6-5.5c0-.6-.1-1.2-.2-1.8c-.2-1.3-.7-2.5-1.6-3.5" />
                                        </svg>

                                        ZOHO Settings
                                    </button>
                                </div>
                            </a>
                        </div>

                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.zoho.index') }}">
                                <div class="inner_grid">

                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                                            <g fill="currentColor">
                                                <path d="M17 18v-2h-2v2H3v6h2v-4h10v4h2v-4h10v4h2v-6z" />
                                                <path d="M4 32a3 3 0 1 1 0-6a3 3 0 0 1 0 6m0-4a1 1 0 1 0 0 2a1 1 0 0 0 0-2m12 4a3 3 0 1 1 0-6a3 3 0 0 1 0 6m0-4a1 1 0 1 0 0 2a1 1 0 0 0 0-2m12 4a3 3 0 1 1 0-6a3 3 0 0 1 0 6m0-4a1 1 0 1 0 0 2a1 1 0 0 0 0-2M23 8V6h-2.1a5 5 0 0 0-.73-1.75l1.49-1.49l-1.42-1.42l-1.49 1.49A5 5 0 0 0 17 2.1V0h-2v2.1a5 5 0 0 0-1.75.73l-1.49-1.49l-1.42 1.42l1.49 1.49A5 5 0 0 0 11.1 6H9v2h2.1a5 5 0 0 0 .73 1.75l-1.49 1.49l1.41 1.41l1.49-1.49a5 5 0 0 0 1.76.74V14h2v-2.1a5 5 0 0 0 1.75-.73l1.49 1.49l1.41-1.41l-1.48-1.5A5 5 0 0 0 20.9 8zm-7 2a3 3 0 1 1 0-6a3 3 0 0 1 0 6" class="ouiIcon__fillSecondary" />
                                                <path d="M16 8a1 1 0 0 1-1-1a1.4 1.4 0 0 1 0-.2a.7.7 0 0 1 .06-.18a.7.7 0 0 1 .09-.18a2 2 0 0 1 .12-.15a.9.9 0 0 1 .33-.21a1 1 0 0 1 1.09.21l.12.15a.8.8 0 0 1 .09.18a.6.6 0 0 1 .1.18a1.3 1.3 0 0 1 0 .2a1 1 0 0 1-1 1" />
                                            </g>
                                        </svg>

                                        ZOHO CRM
                                    </button>
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
