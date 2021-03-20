<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendRequests extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='RequestID';
   protected $table ='tblFriendRequests';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'CreatedOn';
   const UPDATED_AT = 'UpdatedOn';
}
