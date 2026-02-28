<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'centre_id',
        'level_id',
        'teacher_id',
        'capacity',
        'status',
        'language', // <--- DOIT ÊTRE LÀ
        'type',     // <--- DOIT ÊTRE LÀ
        'days',
        'start_time',
        'end_time'
    ];
    protected $casts = [
        'days' => 'array', // Transforme automatiquement le JSON de la BD en tableau PHP
    ];
    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
