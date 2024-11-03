<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use APP\Models\MasterSparePart;
use Database\Seeders\SparePartSeeder;

/**
 * @extends 
 */
class SparePartsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = SparePartSeeder::class;

    public function definition(): array
    {
        return [
            
        ];
    }
}
