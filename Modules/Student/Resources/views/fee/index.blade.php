@extends('user::layouts.master')
@section('title', 'Admin | Student Fees')

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
                <h1>Fees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
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
                        <h3 class="card-title">{{ userName('Student', $student->id) }}'s Fee List</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.fee.create', $student->id) }}" class="btn btn-success">Add Fee</a></div>
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
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.fee.installment.edit', $fee->id) }}" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</a>
                            <a href="{{ route('admin.student.fee.statement', $fee->id) }}" class="btn btn-info">Statement</a>
                            <a href="{{ route('admin.student.fee.statement.print', $fee->id) }}" class="btn btn-info"></i> Print Statement</a>
                        </div>
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
                                            <div class="personal_information_title"> Remaining Setup :</div>
                                            <div class="personal_information_des">{{ $fee->remaining }}</div>
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
                        <table id="example" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
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
                                    <td>
                                        @if ($key == 0)
                                        <b>Enrollment Fee:</b> {{ $value->enrollment_fee }} <br>
                                        <b>Material Fee:</b> {{ $value->material_fee }} <br>
                                        <b>Inital Fee:</b> {{ $fee->initial_fee }} <br>
                                        @endif
                                        <b>Total:</b> {{ $value->amount }}
                                    </td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>
                                        {{ studentPaymentStatus($value->status) }}
                                        @if ($value->status == 2) <br>
                                        <b>Date:</b> {{ dateFormat($value->studentIntakeCourseFeePayment->paid_date) }} <br>
                                        <b>Amount:</b> {{ $value->studentIntakeCourseFeePayment->paid_amount }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1)
                                        <a href="{{ route('admin.student.fee.installment.pay', $value->id) }}" class="btn btn-info btn-sm">Pay</a>
                                        @elseif ($value->status == 2 || $value->status == 3)
                                        <a href="{{ route('admin.student.fee.installment.receipt', $value->id) }}" class="btn btn-info btn-sm">Receipt</a>
                                        <a href="{{ route('admin.student.fee.installment.receipt.print', $value->id) }}" class="btn btn-info btn-sm">Print Receipt</a>
                                        <a href="{{ route('admin.student.fee.installment.pay.edit', $value->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        @if ($value->status == 2)
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-refund{{ $value->id }}">
                                            Refund
                                        </button>
                                        <div class="modal fade" id="modal-refund{{ $value->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $value->name }} Payment Refund</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form id="payrefund" action="{{ route('admin.student.fee.installment.payment.refund', $value->studentIntakeCourseFeePayment->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="refunded_amount">Refund Amount</label> <span class="required">*</span>
                                                                        <input type="text" name="refunded_amount" class="form-control" id="refunded_amount" value="{{ $value->studentIntakeCourseFeePayment->paid_amount }}" readonly required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="comment">Comments</label>
                                                                        <textarea name="comment" id="comment" class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="receipt">Upload Receipt</label>
                                                                        <input type="file" name="receipt" class="form-control" id="receipt" placeholder="Choose Receipt">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="reinstate">Reinstate Payment</label>
                                                                        <input type="checkbox" name="reinstate" class="form-control" id="reinstate" value="1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        @elseif ($value->status == 3)
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
                                                                        <th>Receipt</th>
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
