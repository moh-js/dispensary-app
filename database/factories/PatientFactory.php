<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genders = ['male', 'female'];
        $genderIndex = array_rand($genders);
        $firstName = $this->faker->firstName($genders[$genderIndex]);
        $lastName = $this->faker->lastName();
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'patient_id' => $this->faker->unique()->numberBetween(),
            'phone' => $this->faker->phoneNumber(),
            'dob' => $this->faker->dateTimeBetween('1940-01-01', '2020-12-31'),
            'address' => $this->faker->address(),
            'gender' => $genders[$genderIndex]
        ];
    }
}
