<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisements extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='AdvertisementID';
   protected $table ='tblAdvertisements';
   protected $fillable = [];
   public $timestamps = false;
}
