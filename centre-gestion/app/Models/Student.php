<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'level_id',
        'group_id',
        'tuition_fee_id',
        'registration_date',
        'status',
        'last_transfer_reason'
    ];

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONS                                   */
    /* -------------------------------------------------------------------------- */

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function tuitionFee()
    {
        return $this->belongsTo(TuitionFee::class, 'tuition_fee_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /* -------------------------------------------------------------------------- */
    /*                          INTELLIGENCE FINANCIÈRE                           */
    /* -------------------------------------------------------------------------- */

    /**
     * RÈGLE 1 & 4 : Vérifie si l'inscription est payée.
     * On somme la colonne 'amount_registration' pour le cycle actuel.
     */
    public function hasPaidRegistration()
    {
        if (!$this->tuition_fee_id || !$this->tuitionFee) return false;

        $required = (float) $this->tuitionFee->inscription_fee;

        // Somme de la colonne dédiée à l'inscription
        $totalPaidReg = (float) $this->payments()
            ->where('tuition_fee_id', $this->tuition_fee_id)
            ->sum('amount_registration');

        return $totalPaidReg >= $required;
    }

    /**
     * Calcule le montant total versé pour la scolarité du CYCLE ACTUEL.
     * On somme la colonne 'amount_tuition'.
     */
    public function totalTuitionPaid()
    {
        if (!$this->tuition_fee_id) return 0;

        return (float) $this->payments()
            ->where('tuition_fee_id', $this->tuition_fee_id)
            ->sum('amount_tuition');
    }

    /**
     * RÈGLE 2 : Retourne le solde TOTAL restant (Inscription + Pension)
     * pour le cycle actuel.
     */
    public function getTuitionBalance()
    {
        if (!$this->tuition_fee_id || !$this->tuitionFee) return 0;

        $totalExpected = (float) $this->tuitionFee->total_amount;
        
        // On soustrait tout ce qui a été payé au global pour ce cycle (amount total)
        $totalPaidForCycle = (float) $this->payments()
            ->where('tuition_fee_id', $this->tuition_fee_id)
            ->sum('amount');

        return max(0, $totalExpected - $totalPaidForCycle);
    }

    /**
     * Vérifie si l'étudiant est totalement en règle (Inscription + Pension)
     */
    public function isFinanciallyCleared()
    {
        return $this->hasPaidRegistration() && $this->getTuitionBalance() <= 0;
    }

    /**
     * Vérifie si la Tranche 1 (50% de la scolarité seule) est réglée.
     */
    public function hasClearedFirstTranche()
    {
        if (!$this->hasPaidRegistration() || !$this->tuitionFee) return false;

        $pensionPure = (float) $this->tuitionFee->total_amount - (float) $this->tuitionFee->inscription_fee;
        $target = $pensionPure / 2;
        
        return $this->totalTuitionPaid() >= $target;
    }

    /* -------------------------------------------------------------------------- */
    /*                           LOGIQUE TEMPORELLE (RÈGLE 5)                     */
    /* -------------------------------------------------------------------------- */

    public function getCycleExpirationDate()
    {
        if (!$this->registration_date || !$this->tuitionFee || !$this->tuitionFee->duration_weeks) {
            return null;
        }
        return Carbon::parse($this->registration_date)->addWeeks((int)$this->tuitionFee->duration_weeks);
    }

    public function getCycleProgressPercentage()
    {
        if (!$this->registration_date || !$this->tuitionFee || !$this->tuitionFee->duration_weeks) {
            return 0;
        }
        $start = Carbon::parse($this->registration_date);
        $end = $this->getCycleExpirationDate();
        $totalDays = $start->diffInDays($end);
        $daysPassed = $start->diffInDays(now());
        if ($totalDays <= 0) return 100;
        return min(100, round(($daysPassed / $totalDays) * 100));
    }

    public function isCycleExpired()
    {
        $expiration = $this->getCycleExpirationDate();
        return $expiration ? now()->greaterThan($expiration) : false;
    }

    /* -------------------------------------------------------------------------- */
    /*                           LOGIQUE ACADÉMIQUE                               */
    /* -------------------------------------------------------------------------- */

    public function canBePromoted()
    {
        return $this->hasPaidRegistration() && $this->getTuitionBalance() <= 0;
    }

    public function attendanceRate()
    {
        $total = $this->attendances->count();
        if ($total == 0) return 0;
        $present = $this->attendances->where('present', true)->count();
        return round(($present / $total) * 100, 2);
    }

    /* -------------------------------------------------------------------------- */
    /*                                UTILITAIRES                                 */
    /* -------------------------------------------------------------------------- */

    public function scopeForCurrentCentre($query)
    {
        return $query->where('centre_id', auth()->user()->centre_id);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function totalPaid()
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getActiveTuitionFee()
    {
        return $this->tuitionFee;
    }
}