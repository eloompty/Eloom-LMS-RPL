@extends('student::student.layouts.master')
@section('title', 'Student | Dashboard')

@section('content')
<style>
    .fc-daygrid-event {
        display: grid;
        padding: 0 10px;
    }

    .fc-daygrid-dot-event .fc-event-title {
        white-space: normal !important;
        /* Allows the title to wrap */
    }

    .fc-direction-ltr .fc-daygrid-event .fc-event-time {
        display: block;
        /* Ensure time is displayed in block */
        white-space: normal !important;
        /* Allows the title to wrap */
    }
</style>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Courses</span>
                        <a href="{{ route('student.course.index') }}">
                            <span class="info-box-number">
                                {{ $total_courses }}
                                <small></small>
                            </span>
                        </a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-book"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Units</span>
                        <span class="info-box-number">{{ $total_units }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Assignment</span>
                        <span class="info-box-number">{{ $total_assignments }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <div class="col-md-6">
                <!-- Calendar -->
                <div class="card bg-gradient">
                    <div class="card-header border-0">

                        <h3 class="card-title">
                            <i class="far fa-calendar-alt"></i>
                            Calendar
                        </h3>
                        <!-- tools card -->
                        <div class="card-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('student.calendar.index') }}" class="dropdown-item">View calendar</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body pt-0">
                        <div id="external-events"></div>
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" height="180" style="height: 0px;"></canvas>
                </div>
                <div class="chart-responsive">
                    <canvas id="pieChart" height="0"></canvas>
                </div>

                <!-- /.col -->
            </div>
            <!-- Left col -->
            <div class="col-md-6">
                <!-- TABLE: NOTIFICATION -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Latest Notifications</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            @if(count($notifications) > 0)
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Body</th>
                                        <th>Link</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach($notifications as $index => $value)
                                        <td>{{ $value->title }}</td>
                                        <td>{{ $value->body }}</td>
                                        <td>
                                            @if ($value->type == 'Assignment')
                                            <a href="{{ route('student.assignment.index', $value->link) }}" class="btn btn-info btn-sm"> <i class="fas fa-eye"></i></a>
                                            @elseif ($value->type == 'OnlineClass')
                                            @php $url = 'https://us06web.zoom.us/j/' . $value->link @endphp
                                            <a href="{{ $url }}" class="btn btn-info btn-sm" target="_blank"> Join</a>
                                            @endif
                                        </td>
                                        <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <h3>
                                <center>No Data Found</center>
                            </h3>
                            @endif
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Latest Due Assessments</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <!-- TABLE: LATEST ASSESMENT -->
                        <div class="table-responsive">
                            <table class="table m-0">
                                @if(count($due_submissions) > 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Trainer</th>
                                        <th>Assignment</th>
                                        <th>Submission Due Date</th>
                                        <th>Grade</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($due_submissions as $index => $value)
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>@if ($value->trainer_id == NULL) - @else {{ userName('Trainer', $value->trainer_id) }} @endif</td>
                                        <td>
                                            @if ($value->type == 'file')
                                            <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                            @elseif ($value->type == 'question')
                                            <a href="{{ route('student.assignment.question.index', [$value->id, $value->student_intake_id]) }}"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                            @else
                                            <a href="{{ route('student.assignment.mcq.index', [$value->id, $value->student_intake_id]) }}"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($value->intakeUnit->due_date == NULL)
                                            {{ dateFormat($value->due_date) }}
                                            @else
                                            {{ dateFormat($value->intakeUnit->due_date) }}
                                            @endif
                                        </td>
                                        <td>{{ $value->grade }}</td>
                                        <td>
                                            <div class="row">
                                                @if ($value->type == 'file')
                                                <a href="{{ asset($value->path) }}" class="btn btn-info btn-sm" target="_blank">
                                                    @elseif ($value->type == 'question')
                                                    <a href="{{ route('student.assignment.question.index', [$value->id, $value->student_intake_id]) }}" class="btn btn-info btn-sm" target="_blank">
                                                        @else
                                                        <a href="{{ route('student.assignment.mcq.index', [$value->id, $value->student_intake_id]) }}" class="btn btn-info btn-sm" target="_blank">
                                                            @endif
                                                            <i class="fas fa-eye"></i></a>
                                                        <a href="{{ route('student.submission.index', [$value->id, $value->student_intake_id]) }}" class="btn btn-info btn-sm"> <i class="fas fa-book"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <h3>
                                    <center>No Data Found</center>
                                </h3>
                                @endif
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!--/. container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('scripts')
<script>
    $(function() {

        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
            ele.each(function() {

                // create an Event Object (https://fullcalendar.io/docs/event-object)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                }

                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject)

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                })

            })
        }

        ini_events($('#external-events div.external-event'))

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendar.Draggable;

        var containerEl = document.getElementById('external-events');
        // var checkbox = document.getElementById('drop-remove');
        var calendarEl = document.getElementById('calendar');

        // initialize the external events
        // -----------------------------------------------------------------

        new Draggable(containerEl, {
            itemSelector: '.external-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText,
                    backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                    borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                    textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                };
            }
        });
        $.ajax({
            'url': "{{url('student/calendar/time')}}",
            'method': "GET",
            'contentType': 'application/json',
        }).done(function(data) {
            console.log(data);
            var calendar = new Calendar(calendarEl, {
                // headerToolbar: {
                //     left: 'prev,next today',
                //     center: 'title',
                //     right: 'dayGridMonth,timeGridWeek,timeGridDay'
                // },
                themeSystem: 'bootstrap',
                events: data,
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                eventTimeFormat: { // like 'timeFormat' option
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                eventContent: function(arg) {
                    let timeText = document.createElement('div');
                    timeText.className = 'fc-event-time';
                    timeText.innerText = arg.timeText;

                    let titleText = document.createElement('div');
                    titleText.className = 'fc-event-title';
                    titleText.innerText = arg.event.title;

                    let arrayOfDomNodes = [timeText, titleText];

                    return {
                        domNodes: arrayOfDomNodes
                    };
                },
                drop: function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                }
            });

            calendar.render();
        });
    })
</script>
@endsection