<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CREATED_AT='date_time';
    const UPDATED_AT='updated_at';

    protected $table="items";


    public function category(){
        return $this->belongsTo(Category::class,'item_category');
    }
}
