<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreRatings extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreRatingID';
   protected $table ='tblStoreRatings';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'CommentedOn';
   const UPDATED_AT = 'UpdatedOn';
}
