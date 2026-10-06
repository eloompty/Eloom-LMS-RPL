<?php

namespace Modules\Student\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\Company;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Payment\Entities\Payment;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;

class StudentIntakeCourseFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake course fee.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::find($id);
        if (checkRole('student_intake_course_fee', 'view') == true && $student) {
            $studentIntakeCourses = StudentIntakeCourse::where('student_id', $id)->where('status', 1)->get();
            $intake_course_ids = [];
            foreach ($studentIntakeCourses as $studentIntakeCourse) {
                $intake_course_ids[] = $studentIntakeCourse->intake_course_id;
            }
            $fees = StudentIntakeCourseFee::where('student_id', $id)->whereIn('intake_course_id', $intake_course_ids)->where('status', 1)->orderBy('id', 'asc')->get();
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
            $payments = Payment::where('status', 1)->get();
            $student_agent = StudentAgent::where('student_id', $id)->first();
            if ($student_agent) {
                $agent_commission = $student->studentAgent->agent->rate;
                if ($student->studentAgent->branch == NULL) {
                    $branch_commission = 0;
                } else {
                    $branch_commission = $student->studentAgent->branch->rate;
                }
            } else {
                $agent_commission = 0;
                $branch_commission = 0;
            }
            $today = date('Y-m-d');
            activityLog('Admin', 'Opened ' . userName('Student', $id) . ' Intake Course Fee Menu');
            return view('student::fee.index', compact('student', 'fees', 'payments', 'agent_commission', 'branch_commission', 'today'))->with('no', 1);
        } else {
            return abort(404);
        }
        /* $studentIntakeCourse = StudentIntakeCourse::find($id);
        if (checkRole('student_intake_course_fee', 'view') == true && $studentIntakeCourse) {
            $fees = StudentIntakeCourseFee::where('student_id', $studentIntakeCourse->student_id)->where('intake_course_id', $studentIntakeCourse->intake_course_id)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourse->student_id) . ' Intake Course Fee Menu');
            return view('student::intake.fee.index', compact('studentIntakeCourse', 'fees'))->with('no', 1);
        } else {
            return abort(404);
        } */
    }

    /**
     * Show the form for creating a new student intake course fee.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::find($id);
        if (checkRole('student_intake_course_fee', 'add') == true && $student) {
            $intake_courses = StudentIntakeCourse::where('student_id', $id)->where('status', 1)->get();
            return view('student::fee.create', compact('student', 'intake_courses'));
        } else {
            return redirect()->route('admin.student.fee.index', $id)->with('failure', 'This user does not have permission to add student fee');
        }
        /* $studentIntakeCourse = StudentIntakeCourse::find($id);
        if (checkRole('student_intake_course_fee', 'add') == true && $studentIntakeCourse) {
            $fees = IntakeCourseFee::where('intake_course_id', $studentIntakeCourse->intake_course_id)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourse->student_id) . ' Intake Course Fee Add Page');
            return view('student::intake.fee.create', compact('studentIntakeCourse', 'fees'));
        } else {
            return redirect()->route('admin.student.intake.course.fee.index', $id)->with('failure', 'This user does not have permission to add student fee');
        } */
    }

    /**
     * Store a newly created student intake course fee in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $student = Student::find($id);
        $studentIntakeCourse = StudentIntakeCourseFee::where('student_id', $student->id)->where('intake_course_id', $data['intake_course_id'])->first();
        if ($studentIntakeCourse) {
            return redirect()->back()->with('failure', 'Student Fee with this intake has already been assigned');
        } else {
            $intake_course_fee = IntakeCourseFee::find($data['fee_id']);
            $student_intake_course_fee = StudentIntakeCourseFee::create([
                'student_id' => $id,
                'intake_course_id' => $data['intake_course_id'],
                'name' => $intake_course_fee->name,
                'initial_fee' => $intake_course_fee->initial_fee,
                'enrollment_fee' => $intake_course_fee->enrollment_fee,
                'enrollment_fee_wavier' => $intake_course_fee->enrollment_fee_wavier,
                'material_fee' => $intake_course_fee->material_fee,
                'material_fee_wavier' => $intake_course_fee->material_fee_wavier,
                'fee' => $intake_course_fee->fee,
                'type' => $intake_course_fee->type,
                'due_date' => $intake_course_fee->due_date,
                'status' => $intake_course_fee->status
            ]);
            $intake_course_fee_installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $intake_course_fee->id)->get();
            foreach ($intake_course_fee_installments as $key => $value) {
                StudentIntakeCourseFeeInstallment::create([
                    'student_intake_course_fee_id' => $student_intake_course_fee->id,
                    'name' => $value->name,
                    'enrollment_fee' => $value->enrollment_fee,
                    'material_fee' => $value->material_fee,
                    'amount' => $value->amount,
                    'due_date' => $value->due_date,
                    'status' => $value->status
                ]);
            }
            activityLog('Admin', 'Fee of ' . userName('Student', $student->id) . ' created');
            return redirect()->route('admin.student.fee.index', $id)->with('success', 'Student Fee added successfully');
        }
    }

    /**
     * Show the form for editing the specified student intake course fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentIntakeCourseFee = StudentIntakeCourseFee::findorfail($id);
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourseFee->student_id) . ' Fee Edit Page');
            return view('student::intake.fee.edit', compact('studentIntakeCourseFee'));
        } else {
            return redirect()->route('admin.student.intake.course.fee.index', $studentIntakeCourseFee->student_id)->with('failure', 'This user does not have permission to edit student fee');
        }
    }

    /**
     * Update the specified student intake course fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $studentIntakeCourseFee = StudentIntakeCourseFee::where('id', $id)->first();
        $studentIntakeCourseFee->update($data);
        activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourseFee->student_id) . ' Fee Updates');
        return redirect()->route('admin.student.intake.course.fee.index', $studentIntakeCourseFee->student_id)->with('success', 'Student Fee updated successfully');
    }

    /**
     * Remove the specified student intake course fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /* Get Intake Course Fee By Intake Course Id */
    function getIntakeCourseFee(Request $request)
    {
        $fees = IntakeCourseFee::where('intake_course_id', $request->intake_course_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($fees);
    }

    public function pay($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $today = date('Y-m-d');
            $payments = Payment::where('status', 1)->get();
            $student_agent = StudentAgent::where('student_id', $student->id)->first();
            if ($student_agent) {
                $agent_commission = $student->studentAgent->agent->rate;
                if ($student->studentAgent->branch == NULL) {
                    $branch_commission = 0;
                } else {
                    $branch_commission = $student->studentAgent->branch->rate;
                }
            } else {
                $agent_commission = 0;
                $branch_commission = 0;
            }
            return view('student::fee.pay', compact('installment', 'student', 'today', 'payments', 'agent_commission', 'branch_commission'));
        } else {
            return abort(404);
        }
    }

    public function payEdit($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $payments = Payment::where('status', 1)->get();
            return view('student::fee.edit', compact('installment', 'student', 'payments'));
        } else {
            return abort(404);
        }
    }

    public function receipt($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $company = Company::first();
            return view('student::fee.receipt', compact('installment', 'student', 'company'));
        } else {
            return abort(404);
        }
    }

    public function receiptPrint($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $company = Company::first();
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('student::fee.receipt', compact('installment', 'student', 'company')));

            $file_name = str_replace(" ", "_", userName('Student', $student->id)) . '_' . str_replace(" ", "_", $installment->name) . '_' . '_receipt.pdf';

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream($file_name, array("Attachment" => 1));
        } else {
            return abort(404);
        }
    }

    public function statement($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $studentIntakeCourseFee = StudentIntakeCourseFee::findorfail($id);
            $installments = $studentIntakeCourseFee->installments;
            foreach ($installments as $payment) {
                if ($payment->studentIntakeCourseFeePayment != NULL) {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'paid_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'date' => dateFormat($payment->studentIntakeCourseFeePayment->paid_date)
                    ];
                } else {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->amount,
                        'paid_amount' => 0,
                        'date' => dateFormat($payment->due_date) . ' (Due Date)'
                    ];
                }
            }
            $total_received = array_sum(array_column($installment_payments, 'paid_amount'));
            $balance = $studentIntakeCourseFee->fee - $total_received;
            $student = Student::find($studentIntakeCourseFee->student_id);
            $company = Company::first();
            return view('student::fee.statement', compact('studentIntakeCourseFee', 'installment_payments', 'total_received', 'student', 'company'));
        } else {
            return abort(404);
        }
    }

    public function statementPrint($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $studentIntakeCourseFee = StudentIntakeCourseFee::findorfail($id);
            $installments = $studentIntakeCourseFee->installments;
            foreach ($installments as $payment) {
                if ($payment->studentIntakeCourseFeePayment != NULL) {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'paid_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'date' => dateFormat($payment->studentIntakeCourseFeePayment->paid_date)
                    ];
                } else {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->amount,
                        'paid_amount' => 0,
                        'date' => dateFormat($payment->due_date) . ' (Due Date)'
                    ];
                }
            }
            $total_received = array_sum(array_column($installment_payments, 'paid_amount'));
            $balance = $studentIntakeCourseFee->fee - $total_received;
            $student = Student::find($studentIntakeCourseFee->student_id);
            $company = Company::first();
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('student::fee.statement', compact('studentIntakeCourseFee', 'installment_payments', 'total_received', 'student', 'company')));
            $file_name = str_replace(" ", "_", userName('Student', $student->id)) . '_' . str_replace(" ", "_", $studentIntakeCourseFee->name) . '_' . 'statement.pdf';

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream($file_name, array("Attachment" => 1));
        } else {
            return abort(404);
        }
    }
}
