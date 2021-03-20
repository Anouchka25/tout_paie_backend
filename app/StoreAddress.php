<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreAddress extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreAddressID';
   protected $table ='tblStoreAddress';
   protected $fillable = [];
   public $timestamps = false;
}
