<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreProducts extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreProductID';
   protected $table ='tblStoreProducts';
   protected $fillable = [];
   public $timestamps = false;
}
