<?php

namespace Modules\CertificateTemplate\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'path',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\CertificateTemplate\Database\factories\CertificateTemplateFactory::new();
    }
}
