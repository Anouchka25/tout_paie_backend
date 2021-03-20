<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{   
    protected $conn ='mysql';
    protected $primaryKey ='StoreCategoryID';
    protected $table ='tblStoreCategory';
    protected $fillable = [];
    public $timestamps = false;
}
