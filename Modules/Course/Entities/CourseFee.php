<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'initial_fee',
        'enrollment_fee',
        'enrollment_fee_wavier',
        'material_fee',
        'material_fee_wavier',
        'fee',
        'installment',
        'type',
        'status'
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseFeeFactory::new();
    }
}
