<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Country\Entities\Country;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['building_number', 'flat_unit', 'street_no', 'street_address', 'p_o_box', 'suburb', 'state', 'zip_code', 'country_id', 'type', 'type_id', 'status'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\AddressFactory::new();
    }
}
