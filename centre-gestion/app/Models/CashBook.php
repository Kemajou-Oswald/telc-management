<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'date',
        'solde_veille',
        'total_entrees',
        'total_sorties',
        'solde_final',
        'date_cloture',
        'is_closed',
    ];

    protected $casts = [
        'date' => 'date',
        'date_cloture' => 'datetime',
        'is_closed' => 'boolean',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function scopeForUserCentre($query, $user)
    {
        if ($user->role === 'super_admin') {
            return $query;
        }

        return $query->where('centre_id', $user->centre_id);
    }
}

