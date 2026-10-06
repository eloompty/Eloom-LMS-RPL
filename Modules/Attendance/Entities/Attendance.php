<?php

namespace Modules\Attendance\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\Student;
use Modules\Trainer\Entities\Trainer;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'intake_unit_id',
        'user_id',
        'user_type',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    protected static function newFactory()
    {
        return \Modules\Attendance\Database\factories\AttendanceFactory::new();
    }
}
