<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategoriesItems extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='SCIid';
   protected $table ='store_categories_items';
   protected $fillable = [];
   public $timestamps = false;
}
