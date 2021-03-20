<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Authorizable, CanResetPassword, SoftDeletes;
use HasApiTokens;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $conn ='mysql';
    protected $primaryKey ='UserID';
    protected $table ='tblUsers';
    protected $fillable = [
         'Email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'RememberToken',
    ];
   public $timestamps = true;
   const CREATED_AT = 'CreatedOn';
   const UPDATED_AT = 'UpdatedOn';
}

