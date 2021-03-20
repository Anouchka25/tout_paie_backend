<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDeliveryDetails extends Model
{  
 
   protected $conn ='mysql';
   protected $primaryKey ='OrderDeliveryDetailsID';
   protected $table ='tblOrderDeliveryDetails';
   protected $fillable = [];
   public $timestamps = false;

}
