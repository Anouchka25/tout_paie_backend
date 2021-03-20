<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreDepartment extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreDepartment';
   protected $table ='tblStoreDepartment';
   protected $fillable = [];
   public $timestamps = false;
}
