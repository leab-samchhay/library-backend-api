<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'staff_id',
        'loan_date',
        'due_date',
        'note',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loanDetails()
    {
        return $this->hasMany(LoanDetail::class, 'loan_id');
    }
}
