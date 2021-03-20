<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='ProductCategoryID';
   protected $table ='tblProductCategory';
   protected $fillable = [];
   public $timestamps = false;
}
