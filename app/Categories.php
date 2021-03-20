<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='CId';
   protected $table ='categories';
   protected $fillable = ['name','image'];
   public $timestamps = false;
}
