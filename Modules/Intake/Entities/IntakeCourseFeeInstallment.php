<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeCourseFeeInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_fee_id',
        'name',
        'enrollment_fee',
        'material_fee',
        'amount',
        'due_date',
        'status',
    ];

    function intakeCourseFee()
    {
        return $this->belongsTo(IntakeCourseFee::class, 'intake_course_fee_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseFeeInstallmentFactory::new();
    }
}
