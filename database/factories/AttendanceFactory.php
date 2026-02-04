<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $checkedIn = fake()->dateTimeThisMonth();
        return [
            'employee_id' => Employee::factory(),
            'checked_in_at' => $checkedIn,
            'checked_out_at' => fake()->optional(0.7)->dateTimeBetween($checkedIn, 'now'),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'checked_out_at' => null,
        ]);
    }
}
