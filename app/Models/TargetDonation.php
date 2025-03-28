<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'target_amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
