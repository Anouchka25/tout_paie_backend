<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreID';
   protected $table ='tblStore';
   protected $fillable = [];
   public $timestamps = false;
}
