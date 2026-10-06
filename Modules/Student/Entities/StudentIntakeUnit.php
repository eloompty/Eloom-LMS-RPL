<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;

class StudentIntakeUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_id',
        'intake_unit_id',
        'funding_source_national',
        'funding_source_state_training_authority',
        'delivery_mode',
        'internal',
        'predominant_delivery_mode',
        'commencing',
        'duration',
        'starting_date',
        'ending_date',
        'due_date',
        'sequence',
        'is_complete',
        'outcome',
        'status'
    ];

    function studentIntakeCourse()
    {
        return $this->belongsTo(StudentIntakeCourse::class);
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeUnitFactory::new();
    }
}
