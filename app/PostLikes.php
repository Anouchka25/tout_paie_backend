<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLikes extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='PostLikeID';
   protected $table ='tblPostLikes';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'LikeedOn';
   const UPDATED_AT = 'UpdatedOn';


}
