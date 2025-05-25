<?php

namespace Database\Factories;

use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechnicianFactory extends Factory
{
    public function __construct() {
        $this->faker = $this->faker->locale('id_ID');
    }

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Technician::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fullname' => $this->faker->name(),
            'nik'      => $this->faker->unique()->randomNumber(6, true),
            'email'    => $this->faker->unique()->email(),
            'phone'    => $this->faker->unique()->phoneNumber(),
        ];
    }
}
