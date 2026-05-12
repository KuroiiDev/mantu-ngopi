<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    /** @use HasFactory<\Database\Factories\RestockFactory> */
    use HasFactory;

    protected $fillable = ['supply_id', 'user_id', 'qty_added', 'price'];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
