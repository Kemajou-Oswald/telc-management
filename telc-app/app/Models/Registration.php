<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'student_id',
        'center_id',
        'exam_session_id',
        'registered_by',
        'amount',
        'payment_status',
        'paid_at',
        'status',
        'cancelled_at',
        'cancel_reason',
        'receipt_number',
        'registered_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'registered_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $registration) {
            if (! $registration->registered_by || ! User::query()->whereKey($registration->registered_by)->exists()) {
                $registration->registered_by = static::resolveFallbackRegistrarId();
            }

            if (! $registration->receipt_number) {
                $registration->receipt_number = static::generateReceiptNumber();
            }

            if (! $registration->registered_at) {
                $registration->registered_at = now();
            }

            if (! $registration->payment_status) {
                $registration->payment_status = self::PAYMENT_PENDING;
            }

            if (! $registration->status) {
                $registration->status = self::STATUS_ACTIVE;
            }
        });
    }

    protected static function resolveFallbackRegistrarId(): int
    {
        if (auth()->check()) {
            return (int) auth()->id();
        }

        $existingUserId = User::query()->value('id');
        if ($existingUserId) {
            return (int) $existingUserId;
        }

        $systemUser = User::query()->firstOrCreate(
            ['email' => 'system@telc.local'],
            [
                'name' => 'System Import',
                'password' => Hash::make(Str::random(32)),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );

        return (int) $systemUser->id;
    }

    protected static function generateReceiptNumber(): string
    {
        do {
            $receiptNumber = 'TELC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::query()->where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class)->withTrashed();
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function audits()
    {
        return $this->hasMany(RegistrationAudit::class)->latest();
    }
}
