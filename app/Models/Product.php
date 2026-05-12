<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'image', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplies()
    {
        return $this->belongsToMany(Supply::class, 'product_supply_r')
            ->withPivot('qty', 'unit')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'product_transaction_r')
            ->withPivot('qty', 'price_at_transaction')
            ->withTimestamps();
    }

    public function productStatus(): string
    {
        if ($this->supplies->isEmpty())
            return 'aman';

        foreach ($this->supplies as $supply) {
            if ($supply->qty <= 0)
                return 'habis';
            if ($supply->qty < $supply->pivot->qty)
                return 'habis';
            if ($supply->qty < 10)
                return 'tipis';
        }
        return 'aman';
    }
}