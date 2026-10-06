@extends('student::student.layouts.master')
@section('title', 'Student | Fee')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Fees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Fees</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fee List</h3>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($fees as $index => $fee)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $fee->intakeCourse->course->course_name }}'s {{ $fee->name }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="personal_information">
                                    <!-- <h6 class="personal_in">Personal Information</h6> -->
                                    <div class="personal_information_list">
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Fee :</div>
                                            <div class="personal_information_des">{{ $fee->fee }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Initial Fee :</div>
                                            <div class="personal_information_des">{{ $fee->initial_fee }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Enrollment Fee :</div>
                                            <div class="personal_information_des">{{ $fee->enrollment_fee }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Material Fee :</div>
                                            <div class="personal_information_des">{{ $fee->material_fee }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Total Fee :</div>
                                            <div class="personal_information_des">{{ $fee->total_fee }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Start Date :</div>
                                            <div class="personal_information_des">{{ dateFormat($fee->intakeCourse->starting_date) }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> End Date :</div>
                                            <div class="personal_information_des">{{ dateFormat($fee->intakeCourse->ending_date) }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Paid Amount :</div>
                                            <div class="personal_information_des">{{ $fee->paid_installment }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Remaining Payment :</div>
                                            <div class="personal_information_des">{{ $fee->remaining_installment }}</div>
                                        </div>
                                        <div class="personal_information_devide">
                                            <div class="personal_information_title"> Refunded Amount :</div>
                                            <div class="personal_information_des">{{ $fee->refunded_amount }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($fee->installments->count() > 0)
                        <table id="example3" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Enrollment Fee</th>
                                    <th>Material Fee</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fee->installments as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->enrollment_fee }}</td>
                                    <td>{{ $value->material_fee }}</td>
                                    <td>{{ $value->amount }}</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>{{ studentPaymentStatus($value->status) }}</td>
                                    <td>
                                        @if ($value->status == 1)
                                        
                                        @elseif ($value->status == 2 || $value->status == 3)
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-paid{{ $value->id }}">
                                            Receipt
                                        </button>
                                        <div class="modal fade" id="modal-paid{{ $value->id }}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $value->name }} Receipt</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button id="printButton" class="btn btn-info" onclick="print()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                                                                <table style="width:100%" id="printTable" class="table table-striped table-bordered table-hover" border="2">
                                                                    <tr>
                                                                        <th>Total Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->total_amount }}</td>
                                                                    </tr>
                                                                    @if ($value->enrollment_fee > 0)
                                                                    <tr>
                                                                        <th>Enrollment Fee</th>
                                                                        <td>{{ $value->enrollment_fee }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($value->material_fee > 0)
                                                                    <tr>
                                                                        <th>Material Fee</th>
                                                                        <td>{{ $value->material_fee }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <th>Paid Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->paid_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Paid Date</th>
                                                                        <td>{{ dateFormat($value->studentIntakeCourseFeePayment->paid_date) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Remaining Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->remaining_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Payment Type</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->payment_type }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Reciept</th>
                                                                        <td><img src="{{ asset($value->studentIntakeCourseFeePayment->receipt) }}" alt="" width="200" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Notes</th>
                                                                        <td>
                                                                            @if ($value->studentIntakeCourseFeePayment->paymentnotes->first())
                                                                            {{ $value->studentIntakeCourseFeePayment->paymentnotes->first()->notes }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        @if ($value->status == 3)
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-refunded{{ $value->id }}">
                                            Refund Receipt
                                        </button>
                                        <div class="modal fade" id="modal-refunded{{ $value->id }}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $value->name }} Refund Receipt</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table style="width:100%">
                                                                    <tr>
                                                                        <th>Refunded Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->paymentRefund->refunded_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Refunded Date</th>
                                                                        <td>{{ dateFormat($value->studentIntakeCourseFeePayment->created_at) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Reciept</th>
                                                                        <td><img src="{{ asset($value->studentIntakeCourseFeePayment->paymentRefund->receipt) }}" alt="" width="200" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Comment</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->paymentRefund->comment }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        @endif
                                        @endif
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Enrollment Fee</th>
                                    <th>Material Fee</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        @endforeach
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    window.jsPDF = window.jspdf.jsPDF;
    var docPDF = new jsPDF();

    function print() {
        var elementHTML = document.querySelector("#printTable");
        console.log(elementHTML);
        docPDF.html(elementHTML, {
            callback: function(docPDF) {
                docPDF.save('Receipt.pdf');
            },
            x: 15,
            y: 15,
            width: 170,
            windowWidth: 650
        });
    }
</script>
@endsection