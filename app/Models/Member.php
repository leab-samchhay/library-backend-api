<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_code',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
    ];
}
