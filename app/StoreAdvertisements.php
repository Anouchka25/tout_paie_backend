<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreAdvertisements extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreAdvertisementID';
   protected $table ='tblStoreAdvertisements';
   protected $fillable = [];
   public $timestamps = false;
}
