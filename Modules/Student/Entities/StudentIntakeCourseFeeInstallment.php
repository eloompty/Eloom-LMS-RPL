<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_id',
        'name',
        'enrollment_fee',
        'material_fee',
        'amount',
        'due_date',
        'status'
    ];

    function studentIntakeCourseFee()
    {
        return $this->belongsTo(StudentIntakeCourseFee::class);
    }

    function studentIntakeCourseFeePayment()
    {
        return $this->hasOne(StudentIntakeCourseFeeInstallmentPayment::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentFactory::new();
    }
}
