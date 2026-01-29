<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
          'first_name' => $this->faker->firstName(),
          'last_name'  => $this->faker->lastName(),
          // 1:男性 2:女性 3:その他
          'gender'     => $this->faker->numberBetween(1, 3),
          'email'      => $this->faker->unique()->safeEmail(),
          'tel'        => $this->faker->numerify('0##########'),
          'address'    => $this->faker->address(),
          // building は NULL になることもある想定
          'building'   => $this->faker->optional()->secondaryAddress(),
          'detail'     => $this->faker->realText(120)
    ];
    }
}
