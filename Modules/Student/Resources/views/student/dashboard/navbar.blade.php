<nav class="main-header dark-mode navbar navbar-expand">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('student.dashboard') }}" class="nav-link">Home</a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li>
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel d-flex nav-link">
                <div class="image">
                    <img src="{{ asset(Auth::guard('student')->user()->image) }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="">
                    <a href="#" class="d-block">{{ userName('Student', Auth::guard('student')->user()->id) }}</a>
                </div>
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{ notification('Student')->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ notification('Student')->count() }} Notifications</span>
                @if (notification('Student')->count() > 0)
                @foreach(notification('Student') as $index => $value)
                <div class="dropdown-divider"></div>
                @if ($value->type == 'Assignment')
                <a href="{{ route('student.assignment.index', $value->link) }}" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i>{{ $value->type}}
                    <span class="float-right text-muted text-sm">{{ dateFormat($value->created_at) }}</span>
                </a>
                @else ($value->type == 'OnlineClass')
                <?php $url = 'https://us06web.zoom.us/j/' . $value->link; ?>
                <a href="{{ $url }}" class="dropdown-item">
                    <i class="fas fa-video mr-2"></i>Zoom Class
                    <span class="float-right text-muted text-sm">{{ dateFormat($value->created_at) }}</span>
                </a>
                @endif
                @endforeach
                @endif
                <div class="dropdown-divider"></div>
                <a href="{{ route('student.notification.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>