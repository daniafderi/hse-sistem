<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRecord extends Model
{
    protected $fillable = [
        'loan_item_id',
        'quantity',
        'condition',
        'returned_at',
    ];

    public function loanItem()
    {
        return $this->belongsTo(LoanItem::class);
    }
}

