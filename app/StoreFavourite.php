<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreFavourite extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreFavouritesID';
   protected $table ='tblStoreFavourites';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'CreatedOn';
   const UPDATED_AT = 'UpdatedOn';
}
