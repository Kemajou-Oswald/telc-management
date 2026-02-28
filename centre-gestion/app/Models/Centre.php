<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'city',
        'country',
        'phone',
        'email',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
