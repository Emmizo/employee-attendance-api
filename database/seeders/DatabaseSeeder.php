<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user for API testing
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Example employees + attendance records for reports testing
        $employees = Employee::factory()->count(5)->create();

        // Create a few attendances for today (some checked out, some still open)
        foreach ($employees as $i => $employee) {
            $checkedIn = now()->startOfDay()->addHours(8)->addMinutes($i * 7);
            Attendance::query()->create([
                'employee_id' => $employee->id,
                'user_id' => $admin->id,
                'checked_in_at' => $checkedIn,
                'checked_out_at' => ($i % 2 === 0) ? $checkedIn->copy()->addHours(8) : null,
                'notes' => ($i % 2 === 0) ? 'Seeded attendance (checked out)' : 'Seeded attendance (open)',
            ]);
        }
    }
}
