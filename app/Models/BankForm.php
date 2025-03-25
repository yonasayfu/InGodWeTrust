<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bank_name',
        'form_name',
        'form_file',
    ];
    
    /**
     * Get the URL for the form file.
     *
     * @return string|null
     */
    public function getFormFileUrlAttribute()
    {
        if ($this->form_file) {
            return asset('storage/' . $this->form_file);
        }
        
        return null;
    }
}
