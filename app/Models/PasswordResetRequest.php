<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    /** @use HasFactory<\Database\Factories\PasswordResetRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'new_password',
        'status',
    ];

    protected $hidden = [
        'new_password',
    ];

    protected function casts(): array
    {
        return [
            'new_password' => 'hashed',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
