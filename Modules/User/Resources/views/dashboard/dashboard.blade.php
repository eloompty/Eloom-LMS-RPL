@extends('user::layouts.master')
@section('title', 'Admin | Dashboard')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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
            <a href="{{ route('admin.course.index') }}">
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
          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Students</span>
            <a href="{{ route('admin.student.index') }}"><span class="info-box-number">{{ $total_students }}</span></a>
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
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Trainers</span>
            <a href="{{ route('admin.trainer.index') }}"><span class="info-box-number">{{ $total_trainers }}</span></a>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Intakes</span>
            <a href="{{ route('admin.intake.index') }}"><span class="info-box-number">{{ $total_intakes }}</span></a>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <section class="col-lg-8 connectedSortable">
        <!-- solid sales graph -->
        <div class="card bg-gradient" style="background-color: #a1ccd1;">
          <div class="card-header border-0">
            <h3 class="card-title">
              <i class="fas fa-th mr-1"></i>
              Intake Data
            </h3>

            <div class="card-tools">
              <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
          <!-- /.card-body -->
          <div class="card-footer bg-transparent">
            <div class="row">
              <div class="col-6 text-center">
                <input type="text" class="knob" data-readonly="true" value="{{ $total_intakes }}" data-width="60" data-height="60" data-fgColor="#39CCCC" style="text-align:center">

                <div class="text-white">Intakes</div>
              </div>
              <!-- ./col -->
              <div class="col-6 text-center">
                <input type="text" class="knob" data-readonly="true" value="{{ $total_students }}" data-width="60" data-height="60" data-fgColor="#39CCCC" style="text-align:center">

                <div class="text-white">Students</div>
              </div>
              <!-- ./col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

        <!-- TABLE: LATEST ASSESMENT -->
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Latest Assessments</h3>

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
              <table class="table table-striped m-0">
                @if(count($submissions) > 0)
                <thead>
                  <tr>
                    <th>Student</th>
                    <th>Due Date</th>
                    <th>Submitted Date</th>
                    <th>Trainer</th>
                    <th>Grade</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($submissions as $index => $value)
                  <tr>
                    <td><a href="{{ route('admin.student.show', $value->student_id) }}">{{ userName('Student', $value->student_id) }}</a></td>
                    <td>{{ dateFormat($value->assignment->due_date) }}</td>
                    <td>{{ dateFormat($value->created_at) }}</td>
                    <td>{{ userName('Trainer', $value->assignment->trainer_id) }}</td>
                    <td>@if ($value->assignment_grade_id == NULL) Not graded @else {{ $value->assignmentGrade->name }} @endif</td>
                    <td>
                      <a href="{{ route('admin.submission.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
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
          <div class="card-footer clearfix card-footer-divide">
            <a href="{{ route('admin.submission.index') }}" class="btn btn-sm btn-info float-left">View All Submissions</a>
            <a href="{{ route('admin.assignment.index') }}" class="btn btn-sm btn-secondary float-right">View All Assessments</a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

        <!-- TABLE: STUDENT PAYMENT DUE -->
        <!-- <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Student Due Payments</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          /.card-header
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped m-0">
                @if(count($payment_dues) > 0)
                <thead>
                  <tr>
                    <th>Student</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($payment_dues as $index => $value)
                  <tr>
                    <td><a href="{{ route('admin.student.fee.index', $value->studentIntakeCourseFee->student_id) }}">{{ userName('Student', $value->studentIntakeCourseFee->student_id) }}</a></td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->amount }}</td>
                    <td>{{ dateFormat($value->due_date) }}</td>
                    <td>{{ studentPaymentStatus($value->status) }}</td>
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
            /.table-responsive
          </div>
          /.card-body
          <div class="card-footer clearfix">

          </div>
          /.card-footer
        </div> -->
      </section>
      <!-- /.Left col -->
      <!-- right col (We are only adding the ID to make the widgets sortable)-->
      <section class="col-lg-4 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->

        <div @if (dashboardWidget('top_students_country_dashboard')=='on' ) style="display: block;" @else style="display:none;" @endif>
          <div class="card card-secondary">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Top Students By Country</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex">
                <p class="d-flex flex-column">
                  <span class="text-bold text-lg">{{ $total_students }}</span>
                  <span>Total Students</span>
                </p>
                <p class="ml-auto d-flex flex-column text-right">
                  <!-- <span class="text-success">
                    <i class="fas fa-arrow-up"></i> 33.1%
                  </span>
                  <span class="text-muted">Since last month</span> -->
                </p>
              </div>
              <!-- /.d-flex -->

              <div class="position-relative mb-4">
                <canvas id="sales-chart" height="200"></canvas>
              </div>

              <div class="d-flex flex-row justify-content-end">
                <!-- <span class="mr-2">
                  <i class="fas fa-square text-primary"></i> This year
                </span>
  
                <span>
                  <i class="fas fa-square text-gray"></i> Last year
                </span> -->
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->

        <div @if (dashboardWidget('top_students_agent_dashboard')=='on' ) style="display: block;" @else style="display:none;" @endif>
          <div class="card card-dark">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Top Students By Agent</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="agent-chart" height="200"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->

        <!-- PIE CHART -->
        <div @if (dashboardWidget('students_country_dashboard')=='on' ) style="display: block;" @else style="display:none;" @endif>
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Students By Country</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="pieChart"></canvas>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <!-- /.card -->

        <!-- Calendar -->
        <div class="card">
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
                  <a href="#" class="dropdown-item">Add new event</a>
                  <a href="#" class="dropdown-item">Clear events</a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">View calendar</a>
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
            <!--The calendar -->
            <div id="calendar" style="width: 100%"></div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- TRAINERS LIST -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Trainers</h3>

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
            <ul class="users-list clearfix">
              @if (count($trainers) > 0)
              @foreach ($trainers as $trainer)
              <li>
                <img src="{{ asset($trainer->image) }}" alt="User Image" style="width: 55px; height:55px; object-fit: contain; border: 2px solid #adb5bd; padding: 5px; max-width: initial;">
                <a class="users-list-name" href="#">{{ userName('Trainer', $trainer->id) }}</a>
              </li>
              @endforeach
              @endif
            </ul>
            <!-- /.trainers-list -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer text-center">
            <a href="{{ route('admin.trainer.index') }}">View All Trainers</a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!--/.card -->
      </section>
      <!-- right col -->

    </div>

    <!-- /.col -->
    <!-- Main row -->
    <div class="row">

      <!-- Left col -->
      <div class="col-md-4">

        <div class="chart">
          <!-- Sales Chart Canvas -->
          <canvas id="salesChart" height="180" style="height: 0px;"></canvas>
        </div>
        <!-- <div class="chart-responsive"> -->
        <!-- <canvas id="pieChart" height="0"></canvas> -->
        <!-- </div> -->
      </div>
      <!-- /.col -->

    </div>
    <!-- /.col -->
    <!-- /.row -->
  </div>
  <!--/. container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
  $(function() {
    'use strict'
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    var $salesChart = $('#sales-chart')
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart($salesChart, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($top_country); ?>,
        datasets: [{
            // backgroundColor: '#C98474',
            backgroundColor: ['#C98474', '#7FBCD2', '#A7D2CB', '#A78295', '#C4C1A4'],
            borderColor: '#007bff',
            data: <?php echo json_encode($top_count); ?>,
          },
          // {
          //   backgroundColor: '#ced4da',
          //   borderColor: '#ced4da',
          //   data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
          // }
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            // display: false,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,

              // Include a dollar sign in the ticks
              callback: function(value) {
                if (value >= 1000) {
                  value /= 1000
                  value += 'k'
                }

                // return '$' + value
                return value
              }
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        },
        onClick: function(e) {
          debugger;
          var link = "{{ route('admin.student.index') }}?overseas_country=";
          var activePointLabel = this.getElementsAtEvent(e)[0]._model.label;
          var fullLink = link.concat(activePointLabel);
          // alert(activePointLabel);
          location.href = fullLink;
        }
      }
    })

    var $agentChart = $('#agent-chart')
    // eslint-disable-next-line no-unused-vars
    var agentChart = new Chart($agentChart, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($top_agent); ?>,
        datasets: [{
            backgroundColor: ['#A8A196', '#9BABB8', '#7C96AB', '#413543', '#DBC4F0'],
            borderColor: '#007bff',
            data: <?php echo json_encode($top_agent_count); ?>,
          },
          // {
          //   backgroundColor: '#ced4da',
          //   borderColor: '#ced4da',
          //   data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
          // }
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            // display: false,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,

              // Include a dollar sign in the ticks
              callback: function(value) {
                if (value >= 1000) {
                  value /= 1000
                  value += 'k'
                }

                // return '$' + value
                return value
              }
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        }
      }
    })

    //-------------
    // - PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = {
      labels: <?php echo json_encode($countries); ?>,
      datasets: [{
        data: <?php echo json_encode($student_counts); ?>,
        // backgroundColor: ['#A1CCD1', '#F4F2DE', '#E9B384', '#7C9D96' ,'#C4C1A4'],
        backgroundColor: <?php echo json_encode($colors); ?>,
      }]
    }
    var pieOptions = {
      legend: {
        display: false
      }
    }
    // Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    // eslint-disable-next-line no-unused-vars
    var pieChart = new Chart(pieChartCanvas, {
      type: 'doughnut',
      data: pieData,
      options: pieOptions
    })

    // Sales graph chart
    var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')
    // $('#revenue-chart').get(0).getContext('2d');

    var salesGraphChartData = {
      labels: <?php echo json_encode($chart_intakes); ?>,
      datasets: [{
        label: 'Students',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#11655b',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#ffeebb',
        pointBackgroundColor: '#9ac5f4',
        data: <?php echo json_encode($chart_students); ?>,
      }]
    }

    var salesGraphChartOptions = {
      maintainAspectRatio: false,
      responsive: true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          ticks: {
            fontColor: '#0a4d68'
          },
          gridLines: {
            display: false,
            color: '#088395',
            drawBorder: false
          }
        }],
        yAxes: [{
          ticks: {
            stepSize: 5,
            fontColor: '#0a4d68'
          },
          gridLines: {
            display: true,
            color: '#0a4d68',
            drawBorder: false
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    // eslint-disable-next-line no-unused-vars
    var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
      type: 'line',
      data: salesGraphChartData,
      options: salesGraphChartOptions
    })
  })
</script>
</body>

</html>

@endsection