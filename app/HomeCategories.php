<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeCategories extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='HomeCategoryID';
   protected $table ='tblHomeCategories';
   protected $fillable = [];
   public $timestamps = false;
}
