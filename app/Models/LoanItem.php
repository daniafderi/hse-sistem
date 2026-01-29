<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    protected $fillable =
    ['loan_id', 'tool_id', 'quantity', 'condition_on_return'];
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
    public function returnRecords()
    {
        return $this->hasMany(ReturnRecord::class);
    }
}
