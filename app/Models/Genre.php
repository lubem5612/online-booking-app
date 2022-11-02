<?php

namespace App\Models;

use App\Traits\UseUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory, UseUuid;

    protected $guarded = [
        'id'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}