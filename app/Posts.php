<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='PostID';
   protected $table ='tblPosts';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'PostedOn';
   const UPDATED_AT = 'UpdatedOn';


}
