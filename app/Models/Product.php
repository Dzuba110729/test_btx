<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'article',
        'name',
        'status',
        'data',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public static function rules()
    {
        return [
            'name' => 'required|min:10',
            'article' => 'required|alpha_num|unique:products,article',
            'status' => 'required|in:available,unavailable',
        ];
    }
}
