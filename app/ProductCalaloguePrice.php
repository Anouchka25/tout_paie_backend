<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCalaloguePrice extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='ProductCataloguePriceID';
   protected $table ='tblProductCalaloguePrice';
   protected $fillable = [];
   public $timestamps = false;
}
