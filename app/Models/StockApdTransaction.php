<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockApdTransaction extends Model
{
    protected $table = 'stock_apd_transactions';
    protected $fillable = ['tool_id','type','quantity','note','user_id','stock_before'];

    public function tool() {
        return $this->belongsTo(Tool::class);
    }
}
