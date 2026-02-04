<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'employee_identifier',
        'phone_number',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
