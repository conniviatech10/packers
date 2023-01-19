<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table="item_category";
    
    public $timestamps=false;

    public function items(){
        return $this->hasMany(Items::class,'item_category');
    }

}
