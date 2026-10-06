<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;

class StudentIntakeCourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'intake_course_id',
        'name',
        'initial_fee',
        'enrollment_fee',
        'enrollment_fee_wavier',
        'material_fee',
        'material_fee_wavier',
        'fee',
        'type',
        'due_date',
        'status'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    public function installments()
    {
        return $this->hasMany(StudentIntakeCourseFeeInstallment::class)->orderBy('due_date');
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeFactory::new();
    }
}
