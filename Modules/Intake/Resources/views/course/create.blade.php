@extends('user::layouts.master')
@section('title', 'Admin | Add Intake Course')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Intake Course</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intake->id) }}">Courses</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addintakecourse" action="{{ route('admin.intake.course.store', $intake->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ $intake->name }} Course</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="course_id">Course</label> <span class="required">*</span>
                                <select class="form-control" name="course_id" id="course">
                                    <option value="">-- Select Course --</option>
                                    @foreach($courses as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name">
                            </div>
                            <div class="form-group">
                                <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $intake->starting_date }}">
                            </div>
                            <div class="form-group">
                                <label for="ending_date">Ending Date</label> <span class="required">*</span>
                                <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date">
                            </div>
                            <div class="form-group">
                                <label for="trainer_id">Trainer</label>
                                <select class="form-control" name="trainer_id" id="trainer">
                                    <option value="">-- Select Trainer/Accessor --</option>
                                    @foreach($trainers as $key => $value)
                                    <option value="{{ $value->id }}">{{ userName('Trainer', $value->id) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <table id="unit" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Starting Date</th>
                                <th>Ending Date</th>
                                <th>Due Date</th>
                                <th>Sequence</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
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
        $('#addintakecourse').validate({
            rules: {
                course_id: {
                    required: true,
                },
                reference_name: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                ending_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                course_id: "Please select one course",
                reference_name: "Please enter reference",
                starting_date: "Please enter starting date",
                ending_date: "Please enter ending date",
                status: "Please select one status",

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

    $(document).ready(function() {
        $('#course').on('change', function() {
            var id = $('#course').val();
            var courseID = $(this).val();
            $.ajax({
                'url': "{{url('admin/intake/course/unit/get')}}?course_id=" + id,
                'method': "GET",
                'contentType': 'application/json',
            }).done(function(data) {
                console.log(data);
                let payouts = data;
                var table = $('#unit').DataTable({
                    "lengthMenu": [ 200 ],
                    "aaData": payouts,
                    "columns": [{
                            "data": "id",
                            // "visible": false,
                            render: function(data, type, row) {
                                return '<input class="form-control trackStartingDate" id="unit_id" name="unit_id[]" type="hidden" required value =' + row.id + '>';
                            }
                        },
                        {
                            "data": "code"
                        },
                        {
                            "data": "name"
                        },
                        {
                            "data": "starting_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackStartingDate" id="starting-date" name="unit_starting_date[]" type="date" value =' + row.starting_date + '>';
                            }
                        },
                        {
                            "data": "ending_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackEndingDate" id="ending-date" name="unit_ending_date[]" type="date" value =' + row.ending_date + '>';
                            }
                        },
                        {
                            "data": "due_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackDueDate" id="due-date" name="due_date[]" type="date"  value =' + row.due_date + '>';
                            }
                        },
                        {
                            "data": "sequence",
                            render: function(data, type, row) {
                                return '<input class="form-control trackSequence" id="sequence" name="sequence[]" type="number"  value =' + row.sequence + '>';
                            }
                        },
                        {
                            "data": "status",
                            "class": "td-status",
                            "name": "unit_status[]",
                            "render": function(val, type, row) {
                                return createSelect(val);
                            }
                        }
                    ],
                    columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }],
                    "bDestroy": true,
                    "drawCallback": function(settings) {
                        $(".td-status").on("change", function() {
                            var $row = $(this).parents("tr");
                            var rowData = table.row($row).data();
                            rowData.MarkupValue = $(this).val();
                            console.log($(".td-status option:selected").val());
                        })
                    }
                })
            });
            var dttable = $('#unit').DataTable();
            $('#unit').DataTable().column(0).visible(false);
        });

        function createSelect(selItem) {
            var offices = [0, 1, 3]
            var valuesShown = ['InActive','Active','Locked']
            var sel = "<select name='unit_status[]'><option>Select Status</option>";
            for (var i = 0; i < offices.length; ++i) {
                if (offices[i] == selItem) {
                    sel += "<option selected value = '" + offices[i] + "' >" + valuesShown[i] + "</option>";
                } else {
                    sel += "<option  value = '" + offices[i] + "' >" + valuesShown[i] + "</option>";
                }
            }
            sel += "</select>";
            return sel;
        }
    });
</script>
@endsection