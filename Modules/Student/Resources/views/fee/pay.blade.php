@extends('user::layouts.master')
@section('title', 'Admin | Pay Fee')

@section('content')
<style>
    .costum_tooltip {
        /* padding: 5px; */
        line-height: 0;
        float: inline-end;
    }

    .costum_tooltip i {
        font-size: 18px;

    }

    label {
        font-size: 12px;
    }

    /* .pay_form_devide{
    display: grid;
    grid-template-columns: 1fr 6fr;
    grid-gap:10px;
    margin: 10px 0;
    align-items: center;
   }  */
    .pay_form_input_part input.form-control,
    .pay_form_input_part select.form-control {

        width: 160px;
        font-size: 13px;
    }

    .pay_form_input_part input[type="file"] {
        width: fit-content;
    }

    .paid_amout_devide_part {
        display: flex;
        align-items: center;
        grid-gap: 10px;
    }

    .paid_Ammount_sub_devide {
        display: flex;
        align-items: center;
        grid-gap: 10px;
    }

    .pay_form_input_part input.form-control::placeholder,
    .pay_form_input_part select.form-control::placeholder {
        font-size: 13px;

    }

    .paid_Ammount_sub_devide .sub_diveide_label {
        width: 180px;
        text-align: right;
    }

    .main_devide_point {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-gap: 10px;
        margin: 5px 0;
    }

    .main_devide_point .pay_form_devide {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 10px;
    }

    .checkbox_Devide_point .pay_form_devide {
        display: grid;
        grid-template-columns: 1fr 7.5fr;
        grid-gap: 10px;
        margin: 5px 0;

    }

    .checkbox_Devide_point .pay_form_devide .pay_form_input_part {
        display: flex;
        grid-gap: 10px;
        /* margin:5px 0; */
    }

    .enrollment_type {
        display: flex;
        grid-gap: 10px;
        /* margin:5px 0; */
    }

    .pay_form_lable_part {
        width: 190px;
    }

    @media screen and (max-width: 1650px) {
        .main_devide_point {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .checkbox_Devide_point .pay_form_devide {
            grid-template-columns: 200px auto;
            grid-gap: 0px;
        }
    }

    @media screen and (max-width: 1450px) {
        .main_devide_point {
            grid-template-columns: 1fr 1fr 1fr 1fr;
            align-items: center;
        }

        .checkbox_Devide_point .pay_form_devide {
            grid-template-columns: 1fr;
            grid-gap: 0px;
        }

        .main_devide_point .pay_form_devide {
            grid-template-columns: 1fr;
        }
    }

    @media screen and (max-width: 1150px) {
        .main_devide_point {
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
        }
    }

    @media screen and (max-width: 1030px) {
        .checkbox_Devide_point .pay_form_devide .pay_form_input_part {
            display: grid;
        }
    }

    @media screen and (max-width: 730px) {
        .pay_form_lable_part {
            width: auto;
        }

        .pay_form_input_part input.form-control,
        .pay_form_input_part select.form-control {
            width: 100%;
        }
    }

    @media screen and (max-width: 680px) {
        .main_devide_point {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media screen and (max-width: 530px) {
        .enrollment_type {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }

    @media screen and (max-width: 500px) {
        .main_devide_point {
            grid-template-columns: 1fr;
        }
    }

    @media screen and (max-width: 380px) {
        .enrollment_type {
            display: grid;
            grid-template-columns: 1fr;
        }
    }
</style>
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
                <h1>Pay Student Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.fee.index', $student->id) }}">Fees</a></li>
                    <li class="breadcrumb-item active">Pay</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="paystudentfee" action="{{ route('admin.student.fee.installment.payment', $installment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ userName('Student', $student->id) }}'s Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="total_amount">Total Amount :</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="total_amount" class="form-control" id="total_amount" value="{{ $installment->amount }}" readonly="">
                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="paid_amount">Paid Amount</label> <span class="required">* :</span>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="paid_amount" class="form-control" id="paid_amount" placeholder="Enter Paid Amount">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="received_date">Received Date:</label> <span class="required">*</span>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="date" name="received_date" class="form-control" id="received_date" placeholder="Enter Received Date" value="{{ $today }}" required="">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="paid_date">Actual Paid Date:</label> <span class="required">*</span>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="date" name="paid_date" class="form-control" id="paid_date" placeholder="Enter Actual Paid Date" value="{{ $today }}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="taxable_amount">Taxable Amount:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="taxable_amount" class="form-control" id="taxable_amount" readonly="">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="remaining_amount">Remaining Amount:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="remaining_amount" class="form-control" id="remaining_amount" readonly="">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="payment_type">Payment Type:</label> <span class="required">*</span>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <select class="form-control" name="payment_type" id="payment_type" required="">
                                            <option value="">Payment Type</option>
                                            @foreach($payments as $payment)
                                            <option value="{{ $payment->name }}">{{ $payment->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if ($installment->enrollment_fee > 0)
                            <div class="checkbox_Devide_point">
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="enrollment_fee">Enrollment Fee:
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="enrollment_fee" class="form-control" id="enrollment_fee" value="{{ $installment->enrollment_fee }}" readonly>
                                        <div class="enrollment_type">

                                            <div class="form-check">
                                                <input type="radio" name="enrollment_payment" id="radioPay" class="form-check-input radioPay" value="pay_to_college">
                                                <small><label for="enrollment_pay_to_college">Pay to College</label></small>
                                            </div>
                                            <div class="form-check ">
                                                <input type="radio" name="enrollment_payment" id="radioPay1" class="form-check-input radioPay" value="50/50">
                                                <small><label for="50-50">50/50</label></small>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="enrollment_payment" id="radioPay2" class="form-check-input radioPay" value="discount">
                                                <small><label for="discount_write_off">Discount to student</label></small>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="enrollment_payment" id="radioPay3" class="form-check-input radioPay" value="pay_to_agent">
                                                <small><label for="enrollment_pay_to_agent">Pay to Agent</label></small>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                            @if ($installment->material_fee > 0)
                            <div class="checkbox_Devide_point">
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="material_fee">Material Fee:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="material_fee" class="form-control" id="material_fee" placeholder="Enter Material Fee" value="{{ $installment->material_fee }}" readonly="" required="">
                                        <div class="enrollment_type">

                                            <div class="form-check">
                                                <input type="checkbox" name="material_payment" id="material_payment" class="form-check-input">
                                                <small> <label for="material_pay_to_agent">Pay to Agent</label></small>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="coe">COE:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="number" name="coe" class="form-control" id="coe" placeholder="Enter COE">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="oshc">OSHC:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="number" name="oshc" class="form-control" id="oshc" placeholder="Enter OSHC">
                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="accomodation_placement">Accomodation Placement:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="number" name="accomodation_placement" class="form-control" id="accomodation_placement" placeholder="Enter Accomodation Placement">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="airport_pickup">Airport Pickup:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="number" name="airport_pickup" class="form-control" id="airport_pickup" placeholder="Enter Airport Pickup">
                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="other_fee_title">Other Fees: Title</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="other_fee_title" class="form-control" id="other_fee_title" placeholder="Enter Other Fee Title">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="other_fee">Fee:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="number" name="other_fee" class="form-control" id="other_fee" placeholder="Enter Other Fee">
                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="receipt">Upload Receipt:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="file" name="receipt" class="form-control" id="receipt" placeholder="Choose Receipt">
                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="comment">Comment</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <textarea name="comment" id="commnet" placeholder="Enter Comment" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">
                                <div class="">

                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="radio1" name="optradio" value="net" checked="">Net
                                        <label class="form-check-label" for="radio1"></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="radio2" name="optradio" value="gross">Gross
                                        <label class="form-check-label" for="radio2"></label>
                                    </div>
                                </div>

                            </div>

                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="agent_commission_percent">Agent Commision Percent:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="agent_commission_percent" class="form-control" id="agent_commission_percent" value="{{ $agent_commission }}">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="agent_commission_amount">Agent Commision Amount:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="agent_commission_amount" class="form-control" id="agent_commission_amount" readonly>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="">

                                        <input type="checkbox" name="paid_to_agent" id="paid_to_agent" class="form-check-input">
                                        <small> <label for="paid_to_agent">Agent Commision Paid</label></small>

                                    </div>
                                </div>

                            </div>
                            <div class="main_devide_point">

                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="gst_percent">GST Percent:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="gst_percent" class="form-control" id="gst_percent" value="10">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="gst">GST Amount:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="gst" class="form-control" id="gst" readonly="">
                                    </div>
                                </div>
                                <div class="pay_form_devide">
                                    <div class="pay_form_lable_part">
                                        <label for="gst_comission">GST and Commission:</label>
                                    </div>
                                    <div class="pay_form_input_part">
                                        <input type="text" name="gst_commission" class="form-control" id="gst_comission" readonly="">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="">

                                        <input type="checkbox" name="gst_waiver" id="gst_waiver" class="form-check-input" onclick="onGSTCheck(value);">
                                        <small><label for="gst_waiver">GST Waiver</label> </small>

                                    </div>
                                </div>

                            </div>
                            <input type="hidden" name="branch_commission_percent" class="form-control" id="branch_commission_percent" value="{{ $branch_commission}}" readonly>
                            <input type="hidden" name="branch_commission_amount" class="form-control" id="branch_commission_amount" readonly>
                        </div>
                        <!-- /.card-body -->
                    </div>
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
    $('#paystudentfee').validate({
        rules: {
            total_amount: {
                required: true
            },
            paid_amount: {
                required: true
            },
            received_date: {
                required: true
            },
            paid_date: {
                required: true
            },
            payment_type: {
                required: true
            },
            status: {
                required: true
            },
        },
        messages: {
            total_amount: "Please enter total amount",
            paid_amount: "Please enter paid amount",
            received_date: "Please enter received date",
            paid_date: "Please enter paid date",
            payment_type: "Please select one payment type",
            status: "Please select one status",
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.pay_form_input_part').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    document.getElementById("paid_amount").addEventListener("keyup", checkPaidAmount);

    function checkPaidAmount() {
        var ta = document.getElementById('total_amount');
        var pa = document.getElementById('paid_amount');

        console.log('pa:', pa.value);

        if (parseFloat(pa.value) == 0) {
            alert('Paid Amount cannot be 0')
            return false;
        } else if (parseFloat(ta.value) == 0) {
            alert('Total Amount cannot be 0')
            return false;
        } else if (parseFloat(ta.value) >= parseFloat(pa.value)) {
            var rem = ta.value - pa.value;
            console.log('Remaining: ', rem);
            document.getElementById('remaining_amount').value = rem;

            var tax = pa.value;
            document.getElementById('taxable_amount').value = tax;
            console.log('Taxable: ', tax);

            var agent_tax = document.getElementById('agent_commission_percent').value;
            var at = parseFloat(agent_tax);
            var ata = (parseFloat(pa.value) / 100) * at;
            console.log('ATA', ata);
            var aca = document.getElementById('agent_commission_amount').value = ata;

            var gst_percent = document.getElementById('gst_percent').value;
            var gstp = parseInt(gst_percent);
            var gst = parseInt((aca / 100) * gstp);
            var gsta = document.getElementById('gst').value = gst;

            var gsta = ata + gst;
            document.getElementById('gst_comission').value = gsta;

            var branch_tax = document.getElementById('branch_commission_percent').value;
            var bt = parseFloat(branch_tax);
            var bta = parseInt((aca / 100) * bt);
            document.getElementById('branch_commission_amount').value = bta;

            var acaef = document.getElementById('agent_commission_amount').value;
            var paidAmount = document.getElementById('paid_amount').value;
            var discountAmount = 0;
            var materialFee = 0;
            var enrollmentFee = document.getElementById('agent_commission_percent').value;;
            var comissionPercent = document.getElementById('agent_commission_percent').value;
            var agentComission = document.getElementById('agent_commission_amount').value;
            var branch_tax = document.getElementById('branch_commission_percent').value;
            var taxWavier = false;
            var comissionAmount = 0;

            function checkComissionAmount() {
                var paidAmt = parseFloat(paidAmount);
                console.log("Paid Amount", paidAmt);

                console.log("discount", discountAmount);
                console.log("Comission Amount", comissionPercent);
                console.log(paidAmt * (parseFloat(comissionPercent) / 100));
                console.log("materialFee Amount", materialFee);
                if (parseFloat(comissionPercent) == 0) {
                    comissionAmount = 0;
                } else {
                    comissionAmount = (paidAmt * (parseFloat(comissionPercent) / 100));
                }
                var newComm = (parseFloat(discountAmount)) + parseFloat(materialFee) + comissionAmount;
                console.log("new comission", newComm);
                document.getElementById('agent_commission_amount').value = newComm;
            }

            $(".radioPay").change(function(e) {
                console.log('value:', e.target.value);
                agentPay(e.target.value);
            });


            function agentPay(radioVal) {
                var ef = document.getElementById('enrollment_fee').value;
                var newTotal = acaef
                console.log('ef:', ef);
                console.log('acaef:', acaef);
                if (document.getElementById('material_payment').checked) {
                    var mfef = document.getElementById('material_fee').value;
                } else {
                    var mfef = 0;
                }
                if (radioVal == "pay_to_agent") {
                    document.getElementById('agent_commission_amount').value = parseFloat(acaef) + parseFloat(ef) + parseFloat(materialFee);
                    discountAmount = ef;
                } else if (radioVal == "discount") {
                    document.getElementById('agent_commission_amount').value = parseFloat(acaef) + parseFloat(materialFee);
                    discountAmount = 0;
                } else if (radioVal == "50/50") {
                    var ef50 = parseFloat(ef) / 2;
                    discountAmount = ef50;
                    document.getElementById('agent_commission_amount').value = parseFloat(acaef) + parseFloat(ef50) + parseFloat(materialFee);
                } else {
                    discountAmount = 0;
                    document.getElementById('agent_commission_amount').value = parseFloat(acaef) + parseFloat(materialFee);
                }
                checkComissionAmount();
            }

            $("#material_payment").change(function(e) {
                console.log('value:', e.target.value);
                agentMaterialPay(e.target.value);
            });

            function agentMaterialPay(checkVal) {
                var mf = document.getElementById('material_fee').value;
                var newTotal = acaef
                console.log('mf:', mf);
                console.log('acaef:', acaef);
                console.log('checkVal: ', checkVal);
                if (document.getElementById('radioPay1').checked) {
                    var epmf = document.getElementById('enrollment_fee').value;
                    var epmf = parseFloat(epmf) / 2;
                } else if (document.getElementById('radioPay3').checked) {
                    var epmf = document.getElementById('enrollment_fee').value;
                } else {
                    var epmf = 0;
                }
                if (document.getElementById('material_payment').checked) {
                    var mf = parseFloat(document.getElementById('material_fee').value);
                    document.getElementById('agent_commission_amount').value = parseFloat(acaef) + mf + parseFloat(epmf);
                    materialFee = mf;
                } else {
                    document.getElementById('agent_commission_amount').value = parseFloat(acaef) + parseFloat(epmf)
                    materialFee = 0;
                }
                checkComissionAmount();
            }

            document.getElementById("agent_commission_percent").addEventListener("keyup", checkAgentCommission);

            function checkAgentCommission() {
                var agent_tax_check = document.getElementById('agent_commission_percent');
                var atc = parseFloat(agent_tax_check.value);
                if (atc == 0) {
                    document.getElementById('agent_commission_amount').value = 0;
                    document.getElementById('gst_percent').value = 0;
                    document.getElementById('gst').value = 0;
                    document.getElementById('branch_commission_percent').value = 0;
                    document.getElementById('branch_commission_amount').value = 0;
                    comissionAmount = 0;

                } else if (atc > 100) {
                    alert('Percentange cannot be more than 100')
                } else {
                    
                    var atca = (parseFloat(pa.value) / 100) * atc;
                    console.log('ATCA', atca);
                    comissionAmount = atca;
                    comissionPercent = atc;
                }
                acaef = document.getElementById('agent_commission_amount').value
                checkComissionAmount();
            }

            document.getElementById("gst_percent").addEventListener("keyup", checkGst);

            function checkGst() {
                var gst_percent_check = document.getElementById('gst_percent').value;
                var acag = document.getElementById('agent_commission_amount').value;
                var gstpc = parseInt(gst_percent_check);
                var gstg = parseInt((acag / 100) * gstpc);
                var gstag = document.getElementById('gst').value = gstg;
                var gstagg = parseInt(acag) + gstg;
                document.getElementById('gst_comission').value = gstagg;
            }

            function onGSTCheck(checked) {
                var elm = document.getElementById('gst_waiver');
                if (checked != elm.checked) {
                    elm.click();
                }
            }

            const checkbox = document.getElementById('gst_waiver');
            checkbox.addEventListener('change', (event) => {
                if (event.currentTarget.checked) {
                    document.getElementById('gst_percent').value = 0;
                    document.getElementById('gst').value = 0;
                    document.getElementById('gst_comission').value = document.getElementById('agent_commission_amount').value;
                } else {
                    document.getElementById('gst').value = parseInt((agentComissionAmt / 100) * 10);
                    document.getElementById('gst_comission').value = oldGSTNComission;
                    // document.getElementById('gst_comission') = document.getElementById('agent_commission_amount').value
                }

            })

        } else if (parseFloat(ta.value) < parseFloat(pa.value)) {
            alert('Paid Amount cannot be greater than Total Amount')
            return false;
        }
    }
</script>
@endsection