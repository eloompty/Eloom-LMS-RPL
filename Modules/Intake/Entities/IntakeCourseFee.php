<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeCourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_id',
        'name',
        'initial_fee',
        'enrollment_fee',
        'enrollment_fee_wavier',
        'material_fee',
        'material_fee_wavier',
        'fee',
        'installment',
        'type',
        'due_date',
        'status'
    ];

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    function installments()
    {
        return $this->hasMany(IntakeCourseFeeInstallment::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseFeeFactory::new();
    }
}
