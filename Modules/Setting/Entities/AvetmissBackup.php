<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvetmissBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Setting\Database\factories\AvetmissBackupFactory::new();
    }
}
