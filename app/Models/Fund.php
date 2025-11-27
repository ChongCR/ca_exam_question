<?php

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;
    protected $table = "funds";

    protected $fillable = [
        'name',
    ];
    
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
