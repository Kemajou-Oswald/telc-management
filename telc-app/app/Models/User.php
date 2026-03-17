<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_CENTER_ADMIN = 'center_admin';
    public const ROLE_SECRETARY = 'secretary';

    protected static ?bool $centerAssignmentsSupported = null;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar_path',
        'role',
        'is_blocked',
        'blocked_at',
        'center_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class)->withTrashed();
    }

    public function centers()
    {
        return $this->belongsToMany(Center::class)->withTimestamps();
    }

    public static function supportsCenterAssignments(): bool
    {
        if (static::$centerAssignmentsSupported === null) {
            static::$centerAssignmentsSupported = Schema::hasTable('center_user');
        }

        return static::$centerAssignmentsSupported;
    }

    public function primeCentersRelation(): self
    {
        $this->loadMissing('center');

        if (static::supportsCenterAssignments()) {
            $this->loadMissing('centers');

            return $this;
        }

        $fallbackCenters = $this->center ? collect([$this->center]) : collect();
        $this->setRelation('centers', $fallbackCenters);

        return $this;
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'registered_by');
    }

    public function submittedDeposits()
    {
        return $this->hasMany(BankDeposit::class, 'submitted_by');
    }

    public function validatedDeposits()
    {
        return $this->hasMany(BankDeposit::class, 'validated_by');
    }

    public function audits()
    {
        return $this->hasMany(UserAudit::class, 'affected_user_id')->latest();
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isCenterAdmin()
    {
        return $this->role === self::ROLE_CENTER_ADMIN;
    }

    public function isSecretary()
    {
        return $this->role === self::ROLE_SECRETARY;
    }

    public function accessibleCenterIds()
    {
        if ($this->isSuperAdmin()) {
            return [];
        }

        $ids = [];

        if (static::supportsCenterAssignments()) {
            $ids = $this->centers()->pluck('centers.id')->all();
        }

        if ($this->center_id) {
            $ids[] = $this->center_id;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    public function accessibleCenters()
    {
        if ($this->isSuperAdmin()) {
            return Center::query()->where('is_active', true)->orderBy('name')->get();
        }

        $ids = $this->accessibleCenterIds();

        if (empty($ids)) {
            return collect();
        }

        return Center::query()->whereIn('id', $ids)->orderBy('name')->get();
    }

    public function hasMultipleCenters()
    {
        return count($this->accessibleCenterIds()) > 1;
    }
}

