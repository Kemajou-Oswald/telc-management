<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'group_id',
        'date',
        'arrival_time',
        'validated',
        'validated_by'
    ];
    public static function teacherRate($teacherId)
    {
        $totalDays = self::where('teacher_id', $teacherId)->count();
        $validatedDays = self::where('teacher_id', $teacherId)
            ->where('validated', true)
            ->count();

        if ($totalDays == 0) return 0;

        return round(($validatedDays / $totalDays) * 100, 2);
    }
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    use HasFactory;
}
