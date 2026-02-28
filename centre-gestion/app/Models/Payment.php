<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'centre_id',
        'tuition_fee_id',
        'amount',
        'amount_registration', // <--- Ajouter ici
        'amount_tuition',      // <--- Ajouter ici
        'mode',
        'reference',
        'type',
        'tranche',
        'payment_date',
        'month',
        'year',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function tuitionFee()
    {
        return $this->belongsTo(TuitionFee::class);
    }
}
