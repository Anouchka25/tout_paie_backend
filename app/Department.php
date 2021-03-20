<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='DepartmentID';
   protected $table ='tblDepartment';
   protected $fillable = ['DepartmentName','DepartmentCode'];
   public $timestamps = false;
}
