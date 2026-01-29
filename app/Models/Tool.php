<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tool extends Model
{
    protected $fillable =
    ['name', 'stock'];

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }
}
