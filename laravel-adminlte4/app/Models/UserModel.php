<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Specify the table name if it's not the default 'users'
    protected $primaryKey = 'id'; // Specify the primary key if it's not 'id'
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    public $timestamps = true; // Enable timestamps if your table has created_at and updated_at columns

}
