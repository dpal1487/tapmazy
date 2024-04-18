<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'date_of_joining', 'emergency_number', 'pan_number', 'father_name', 'salary', 'last_working_day', 'department_id', 'user_id'];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function address()
    {
        return $this->hasOne(EmployeeAddress::class, 'employee_id', 'id');
    }
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }


    public static function boot()
    {
        parent::boot();
        static::deleting(function ($employee) {
            // before delete() method call this
            $employee->user()->delete();
            // do the rest of the cleanup...
        });
    }
}
