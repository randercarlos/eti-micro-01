<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->company();
        return [
            'category_id' => Category::factory()->create(),
            'name' => $name,
            'url' => $this->faker->unique()->url,
            'phone' => $this->faker->unique()->phoneNumber,
            'email'=> $this->faker->unique()->companyEmail,
            'whatsapp' => $this->faker->unique()->phoneNumber,
            'facebook' => 'https://facebook.com/' . Str::slug($name),
            'instagram' => 'https://instagram.com/' . Str::slug($name),
            'youtube' => 'https://youtube.com/' . Str::slug($name),
        ];
    }
}
