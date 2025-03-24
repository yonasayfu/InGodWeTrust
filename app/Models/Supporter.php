<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'gender',
        'address',
        'phone_number',
        'email',
        'support_type',
        'contribution_amount',
        'commit_duration',
        'start_date',
        'bank_details',
        'testimonial_content',
        'photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
        'contribution_amount' => 'decimal:2',
        'commit_duration' => 'integer',
        'start_date' => 'date',
    ];
}
