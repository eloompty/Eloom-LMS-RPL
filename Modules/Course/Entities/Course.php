<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'details',
        'course_code',
        'cricos_code',
        'entry_requirements',
        'pathways',
        'reference_name',
        'delivery_mode',
        'internal',
        'predominant_delivery_mode',
        'duration',
        'study_period',
        'study_break',
        'hours',
        'fee',
        'fee_initial',
        'fee_installment',
        'onshore_fee',
        'onshore_initial',
        'onshore_installment',
        'enrollment_fee',
        'material_fee',
        'total_units',
        'registered',
        'status',
    ];

    function unit() {
        return $this->hasMany(Unit::class);
    }

    function deliverSite()
    {
        return $this->hasOne(CourseDeliverySite::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseFactory::new();
    }
}
