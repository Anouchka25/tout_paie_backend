<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryType extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='prod_cat_type_id';
   protected $table ='product_category_type';
   protected $fillable = [];
   public $timestamps = false;
}
