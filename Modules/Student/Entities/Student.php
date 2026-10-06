<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Address\Entities\Address;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Attendance\Entities\Attendance;
use Modules\Country\Entities\Country;
use Modules\Email\Entities\EmailUser;
use Modules\Social\Entities\Social;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'student';

    protected $fillable = [
        'salutation',
        'first_name',
        'family_name',
        'date_of_birth',
        'passport_no',
        'citizenship',
        'phone',
        'mobile',
        'email',
        'password',
        'image',
        'id_no',
        'country_id',
        'overseas_address',
        'overseas_country_id',
        'emergency_contact_person',
        'emergency_contact_number',
        'emergency_contact_relation',
        'status',
        'is_enrolled',
        'remarks',
        'file',
        'school_based_flag',
        'school_level_identifier',
        'school_type_identifier',
        'specific_funding_identifier',
        'statistical_area_level_1_identifier',
        'statistical_area_level_2_identifier',
        'high_school_level_completed_identifier',
        'hours_attended',
        'indigenous_status_identifier',
        'language_identifier',
        'unique_student_identifier',
        'phone_work',
        'email_alternative',
        'gender',
        'disability_flag',
        'disability_identifier',
        'survey_contact_status',
        'prior_education',
        'prior_education_achievement_identifier',
        'funding_source_national',
        'funding_source_state_training_authority',
        'at_school',
        'anzsco',
        'anzsic',
        'student_id_national',
        'student_id_apprenticeships',
        'study_reason',
        'labour_force_status_identifier',
        'code',
        'citizenship_country',
        'student_type',
        'allow_submission_after_due_date'
    ];

    function country()
    {
        return $this->belongsTo(Country::class);
    }

    function overseasCountry()
    {
        return $this->belongsTo(Country::class, 'overseas_country_id', 'id');
    }

    function address()
    {
        return $this->hasOne(Address::class, 'type_id', 'id')->where('type', 'student');
    }

    function intake()
    {
        return $this->hasMany(StudentIntakeCourse::class);
    }

    function credit()
    {
        return $this->hasMany(StudentCredit::class);
    }

    public function social()
    {
        return $this->hasMany(Social::class, 'user_id', 'id')->where('user_type', 'Student');
    }

    function devices()
    {
        return $this->hasMany(StudentDevice::class);
    }

    function deliverySite()
    {
        return $this->hasMany(StudentDeliverySite::class);
    }

    function assignmentSubmission()
    {
        return $this->hasMany(AssignmentSubmission::class)->orderBy('id', 'desc');
    }

    function notes()
    {
        return $this->hasMany(StudentNote::class);
    }

    function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    function fees()
    {
        return $this->hasMany(StudentIntakeCourseFee::class);
    }

    function studentAgent()
    {
        return $this->hasOne(StudentAgent::class);
    }

    function studentEmail()
    {
        return $this->hasMany(EmailUser::class, 'user_id', 'id')->where('user_type', 'Student');
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentFactory::new();
    }
}
