<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreComments extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='StoreCommentID';
   protected $table ='tblStoreComments';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'CommentedOn';
   const UPDATED_AT = 'UpdatedOn';
}
