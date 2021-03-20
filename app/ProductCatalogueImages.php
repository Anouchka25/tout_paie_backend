<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCatalogueImages extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='ProductCatalogueImageID';
   protected $table ='tblProductCatalogueImages';
   protected $fillable = [];
   public $timestamps = false;
   
}
