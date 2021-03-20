<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComments extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='PostCommentID';
   protected $table ='tblPostComments';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'CommentedOn';
   const UPDATED_AT = 'UpdatedOn';
}
