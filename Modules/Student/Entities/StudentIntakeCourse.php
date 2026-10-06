<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;

class StudentIntakeCourse extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'intake_course_id', 'starting_date', 'ending_date', 'duration', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    public function studentIntakeUnit()
    {
        return $this->hasMany(StudentIntakeUnit::class);
    }

    public function studentIntakeCourseCompetence()
    {
        return $this->hasOne(StudentIntakeCourseCompetence::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFactory::new();
    }
}
