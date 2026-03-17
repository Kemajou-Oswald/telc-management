<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ExamSession extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';
    public const LEVELS = ['B1', 'B2', 'C1'];

    protected $fillable = [
        'title',
        'center_id',
        'level',
        'city',
        'price',
        'exam_date',
        'opens_at',
        'closes_at',
        'status',
        'notes',
        'archived_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'exam_date' => 'date',
        'opens_at' => 'date',
        'closes_at' => 'date',
        'archived_at' => 'datetime',
    ];

    protected static ?bool $archivingSupported = null;
    protected static ?bool $multiCentersSupported = null;

    protected static function booted()
    {
        static::creating(function (self $session) {
            if ($session->center_id && ! $session->city) {
                $session->city = optional(Center::find($session->center_id))->city;
            }

            if ($session->price === null) {
                $session->price = 0;
            }

            if (! $session->status) {
                $session->status = self::STATUS_DRAFT;
            }
        });
    }

    public static function supportsArchiving(): bool
    {
        if (static::$archivingSupported === null) {
            static::$archivingSupported = Schema::hasColumn('exam_sessions', 'archived_at');
        }

        return static::$archivingSupported;
    }

    public static function supportsMultiCenters(): bool
    {
        if (static::$multiCentersSupported === null) {
            static::$multiCentersSupported = Schema::hasTable('exam_session_centers');
        }

        return static::$multiCentersSupported;
    }

    public function center()
    {
        return $this->belongsTo(Center::class)->withTrashed();
    }

    public function centers()
    {
        return $this->belongsToMany(Center::class, 'exam_session_centers')
            ->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function bankDeposits()
    {
        return $this->hasMany(BankDeposit::class);
    }

    public function isOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isArchived()
    {
        if (! static::supportsArchiving()) {
            return false;
        }

        return !is_null($this->archived_at);
    }

    public function scopeActive($query)
    {
        if (! static::supportsArchiving()) {
            return $query;
        }

        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        if (! static::supportsArchiving()) {
            return $query;
        }

        return $query->whereNotNull('archived_at');
    }

    public function getCentersListAttribute()
    {
        return $this->centers->pluck('name')->implode(', ');
    }
}
