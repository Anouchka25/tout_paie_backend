<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCatalogue extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='ProductCatalogueID';
   protected $table ='tblProductCatalogue';
   protected $fillable = [];
   public $timestamps = false;
}
