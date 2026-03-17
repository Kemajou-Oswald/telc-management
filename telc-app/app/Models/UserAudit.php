<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'affected_user_id',
        'performed_by',
        'action',
        'details',
    ];

    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_user_id')->withTrashed();
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by')->withTrashed();
    }
}
