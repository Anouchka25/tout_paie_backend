<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBasket extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='ProductBasketID';
   protected $table ='tblProductBasket';
   protected $fillable = [];
   public $timestamps = false;
}
