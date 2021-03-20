<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='OrderStatusId';
   protected $table ='tblOrderStatus';
   protected $fillable = [];
   public $timestamps = false;
}
