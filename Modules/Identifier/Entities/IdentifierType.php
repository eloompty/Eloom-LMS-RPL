<?php

namespace Modules\Identifier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IdentifierType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Identifier\Database\factories\IdentifierTypeFactory::new();
    }
}
