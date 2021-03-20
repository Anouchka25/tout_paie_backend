<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostDocument extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='PostDocumentId';
   protected $table ='tblPostDocuments';
   protected $fillable = [];
   public $timestamps = false;
   
}
