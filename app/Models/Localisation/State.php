<?php

namespace App\Models\Localisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table='state';
    protected $primaryKey='state_id';

    public $timestamps=false;

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    /*public function getCountryAttribute(){
        return $this->country->name;
    }*/
}
