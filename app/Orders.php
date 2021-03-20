<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='OrderID';
   protected $table ='tblOrders';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'OrderDate';
   const UPDATED_AT = 'UpdatedOn';

}
