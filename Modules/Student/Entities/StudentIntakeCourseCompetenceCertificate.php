<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateTemplate\Entities\CertificateTemplate;

class StudentIntakeCourseCompetenceCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_competence_id',
        'certificate_template_id',
        'certificate_type',
        'expiry_date',
        'path',
        'status'
    ];

    function studentIntakeCourseCompetence()
    {
        return $this->belongsTo(StudentIntakeCourseCompetence::class, 'student_intake_course_competence_id');
    }

    function certifateTemplate()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseCompetenceCertificateFactory::new();
    }
}
