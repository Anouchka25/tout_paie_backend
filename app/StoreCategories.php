<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategories extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='SCid';
   protected $table ='store_categories';
   protected $fillable = [];
   public $timestamps = false;
}
