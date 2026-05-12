<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'qty', 'unit', 'price', 'reserved'];

    public function restocks()
    {
        return $this->hasMany(Restock::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supply_r')
            ->withPivot('qty', 'unit')
            ->withTimestamps();
    }

    public function stockStatus(): string
    {
        if ($this->qty <= 0)
            return 'habis';
        if ($this->qty < 10)
            return 'tipis';
        return 'aman';
    }

    public function effectiveQty(): float
    {
        return $this->qty - $this->reserved;
    }

    public function isAvailableFor(float $needed): bool
    {
        return $this->effectiveQty() >= $needed;
    }
}