<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='OrderItemID';
   protected $table ='tblOrderItems';
   protected $fillable = [];
   public $timestamps = false;

}
