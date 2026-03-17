<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'center_id',
        'exam_session_id',
        'submitted_by',
        'validated_by',
        'amount',
        'deposit_date',
        'slip_path',
        'status',
        'admin_note',
        'validated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deposit_date' => 'date',
        'validated_at' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class)->withTrashed();
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function audits()
    {
        return $this->hasMany(BankDepositAudit::class)->latest();
    }
}
