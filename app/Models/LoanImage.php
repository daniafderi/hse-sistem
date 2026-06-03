<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanImage extends Model
{
    protected $fillable =
    ['loan_id', 'action', 'image_path'];

    public function loan()
{
    return $this->belongsTo(Loan::class);
}
}
