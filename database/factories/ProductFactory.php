<?php

namespace Database\Factories;

use App\Models\Product;
use Database\Seeders\LocalImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = fake()->unique()->catchPhrase(),
            'price' => fake()->randomFloat(2, 80, 400),
            'created_at' => fake()->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => fake()->dateTimeBetween('-5 month', 'now'),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return ProductFactory
     */
    public function configure(): ProductFactory
    {
        return $this->afterCreating(function (Product $product) {
            try {
                $product
                    ->addMedia(LocalImages::getRandomFile())
                    ->preservingOriginal()
                    ->toMediaCollection('product-images');
            } catch (UnreachableUrl $exception) {
                return;
            }
        });
    }
}
