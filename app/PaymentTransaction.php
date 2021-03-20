<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{   
   protected $conn ='mysql';
   protected $primaryKey ='TransactionID';
   protected $table ='tblPaymentTransaction';
   protected $fillable = [];
   public $timestamps = false;
}
