<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentInformation extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='PaymentInformationId';
   protected $table ='tblPaymentInformation';
   protected $fillable = [];
   public $timestamps = true;
   const CREATED_AT = 'PayOn';
   const UPDATED_AT = 'UpdatedOn';
}
