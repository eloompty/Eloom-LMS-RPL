<?php

namespace Modules\Identifier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Identifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier_type_id',
        'value',
        'description',
        'status'
    ];

    function identifierType()
    {
        return $this->belongsTo(IdentifierType::class, 'identifier_type_id', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\Identifier\Database\factories\IdentifierFactory::new();
    }
}
