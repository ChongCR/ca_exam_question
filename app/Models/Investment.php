<?php

/**
 * @property int $id
 * @property string $uid
 * @property \Illuminate\Support\Carbon $start_date
 * @property decimal $capital_amount
 * @property string $status
 * @property int $fund_id
 * @property int $investor_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $table = "investments";

    protected $fillable = [
        'uid',
        'start_date',
        'capital_amount',
        'status',
        'fund_id',
        'investor_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'capital_amount' => 'decimal:2',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}
