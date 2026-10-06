<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Entities\Payment;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;

class FeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Fee menu from web');
        $id = Auth::guard('student')->user()->id;
        $fees = StudentIntakeCourseFee::where('student_id', $id)->orderBy('id', 'asc')->get();
        foreach ($fees as $key => $value) {
            $fees[$key]['installments'] = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $value->id)->orderBy('due_date')->get();
            $p_amount = [];
            $r_amount = [];
            foreach ($fees[$key]['installments'] as $installment) {
                $paid = $installment->studentIntakeCourseFeePayment;
                if ($paid) {
                    $p_amount[] = $paid->paid_amount;
                    $r_amount[] = $paid->remaining_amount;
                    if ($paid->paymentRefund && $paid->paymentRefund->reinstate == 1) {
                        $ri_amount[] = $paid->paid_amount;
                    } else {
                        $ri_amount[] = 0;
                    }
                } else {
                    $p_amount[] = 0;
                    $r_amount[] = 0;
                    $ri_amount[] = 0;
                }
                if ($installment->status == 3) {
                    $rf_amount[] = $installment->amount;
                } else {
                    $rf_amount[] = 0;
                }
            }
            if ($value->enrollment_fee_wavier == 1) {
                $fees[$key]['enrollment_fee'] = 0;
            } else {
                $fees[$key]['enrollment_fee'] = $value->enrollment_fee;
            }
            if ($value->material_fee_wavier == 1) {
                $fees[$key]['material_fee'] = 0;
            } else {
                $fees[$key]['material_fee'] = $value->material_fee;
            }
            $fee = $value->fee;
            $fees[$key]['total_fee'] = $fees[$key]['enrollment_fee'] + $fees[$key]['material_fee'] + $fee;
            $fees[$key]['total_intsallment'] = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $value->id)->sum('amount');
            $fees[$key]['paid_installment'] = array_sum($p_amount);
            $fees[$key]['remaining'] = $fees[$key]['total_fee'] - ($fees[$key]['total_intsallment'] - array_sum($r_amount) - array_sum($ri_amount));
            $fees[$key]['remaining_installment'] = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $value->id)->where('status', 1)->sum('amount');
            $fees[$key]['refunded_amount'] = array_sum($rf_amount);
            $fees[$key]['student_intake_course'] = StudentIntakeCourse::where('student_id', $id)->where('intake_course_id', $value->intake_course_id)->first();
        }
        return view('student::student.fee.index', compact('fees'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('student::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('student::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
