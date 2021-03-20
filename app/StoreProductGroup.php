<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreProductGroup extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreProductGroupID';
   protected $table ='tblStoreProductGroup';
   protected $fillable = [];
   public $timestamps = false;
}
