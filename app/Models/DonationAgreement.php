<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationAgreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supporter_id',
        'donation_type',
        'donation_amount',
        'recurring_interval',
        'bank_id',
        'signed_agreement_pdf',
        'summary_pdf',
    ];

    // Define relationships (if needed, based on your application logic)
    public function supporter()
    {
        return $this->belongsTo(Supporter::class);
    }

    public function bankForm()
    {
        return $this->belongsTo(BankForm::class, 'bank_id');
    }

    // Example of a scope (you can add more as needed)
    public function scopeSearch($query, $field, $term)
    {
        return $query->where($field, 'LIKE', '%' . $term . '%');
    }
}