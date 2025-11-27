<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $table = "investors";
    protected $fillable = [
        'name',
        'email',
        'contact_number',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
