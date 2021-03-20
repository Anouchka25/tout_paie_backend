<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertisementsImages extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='AdvertisementsPhotoID';
   protected $table ='tblAdvertisementsImages';
   protected $fillable = [];
   public $timestamps = false;
}
