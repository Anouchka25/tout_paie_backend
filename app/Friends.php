<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='RelationID';
   protected $table ='tblFriends';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'CreatedOn';
   const UPDATED_AT = 'UpdatedOn';
}
