<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'level_id',
        'language',
        'label',
        'total_amount',
        'inscription_fee',
        'currency',
        'duration_weeks',
        'duration_label',
        'course_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /** Frais d'inscription (défaut 10 000) */
    public function getInscriptionFeeAttribute($value)
    {
        return $value !== null ? (float) $value : 10000;
    }

    /** Montant restant après inscription (réparti en 2 tranches) */
    public function getRemainingAfterInscription()
    {
        return (float) $this->total_amount - (float) ($this->inscription_fee ?? 10000);
    }

    /** Montant cible tranche 1 = inscription + moitié du restant */
    public function getTranche1Target()
    {
        $inscription = (float) ($this->inscription_fee ?? 10000);
        $rest = (float) $this->total_amount - $inscription;
        return $inscription + $rest / 2;
    }

    /** Montant cible tranche 2 = total */
    public function getTranche2Target()
    {
        return (float) $this->total_amount;
    }

    public static function courseTypes()
    {
        return ['standard' => 'Cours standard', 'vorbereitung' => 'Vorbereitung (prépa Telc)'];
    }

    public static function languageOptions()
    {
        return ['Allemand' => 'Allemand', 'Anglais' => 'Anglais', 'Italien' => 'Italien'];
    }
}

