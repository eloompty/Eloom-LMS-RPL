<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallmentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_installment_id',
        'taxable_amount', // total_amount - (enrollment_fee + material_fee)
        'total_amount',
        'enrollment_fee_agent',
        'material_fee_agent',
        'agent_commission_percent',
        'agent_commission_amount',
        'gst',
        'gst_percent',
        'gst_waiver',
        'paid_to_agent',
        'branch_commission_percent',
        'branch_commission_amount',
        'student_discount',
        'payment_type',
        'coe',
        'oshc',
        'accomodation_placement',
        'airport_pickup',
        'other_fee_title',
        'other_fee',
        'paid_amount',
        'remaining_amount',
        'paid_date',
        'received_date',
        'receipt',
        'status'
    ];

    function studentIntakeCourseFeeInstallment()
    {
        return $this->belongsTo(StudentIntakeCourseFeeInstallment::class);
    }

    function paymentnotes()
    {
        return $this->hasMany(StudentIntakeCourseFeeInstallmentPaymentNote::class);
    }

    function paymentRefund()
    {
        return $this->hasOne(StudentIntakeCourseFeeInstallmentPaymentRefund::class);
    }

    function agentCommissionPayment()
    {
        return $this->hasOne(StudentIntakeCourseFeeInstallmentPaymentCommission::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentPaymentFactory::new();
    }
}
