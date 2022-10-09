<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = User::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name' => $this->faker->firstName,
			'email' => $this->faker->unique()->safeEmail,
			'password' =>  '123456',
			'role_id'	=> null,
			'user_type'	=> $this->faker->randomElement(['customers', 'supplier'])
		];
	}
}
