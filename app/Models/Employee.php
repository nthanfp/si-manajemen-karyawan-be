<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'position',
        'date_of_birth',
        'date_joined',
        'address',
        'photo_path',
        'status',
        'department_id',
        'role_id',
    ];

    protected $dates = [
        'date_of_birth',
        'date_joined',
        'deleted_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
        $this->attributes['full_name'] = $value . ' ' . ($this->attributes['last_name'] ?? '');
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;
        $this->attributes['full_name'] = ($this->attributes['first_name'] ?? '') . ' ' . $value;
    }
}
