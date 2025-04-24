<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   /*  Table property {
        id serial [primary key]
        user_id int [ref: > user.id]
        category_id enum("Praia", "Reserva", "Loja", "Terreno", "Residencial", "Escritorio", "Quartos","Armazem")
        title varchar
        type varchar [null, note: "Casa, Apartamento, Armazem, Loja, Terreno, ..."]
        status varchar [note: "usado,novo etc"]
        type_of_business enum("A","V") [note: "A - Alugar  V - Venda"]
        furnished  enum("yes","no") [note: "Mobilada? Não"]
        country varchar
        address varchar
        city varchar
        province varchar
        location array [null, note: "[latitude & longitude]"]
        length decimal [null, note: "comprimento"]  
        width decimal [null, note: "largura"]
        description text
        room int [null]
        bathroom int [null]
        useful_sand decimal
        price decimal
        announces bool [default: false]
        favorite bool [default: false]
        deleted bool [default: false]
      } */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->create()->id,
            'category_id' => $this->faker->randomElement(['Praia', 'Reserva', 'Loja', 'Terreno', 'Residencial', 'Escritorio', 'Quartos', 'Armazem']),
            'title' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['Casa', 'Apartamento', 'Armazem', 'Loja', 'Terreno']),
            'status' => $this->faker->randomElement(['usado', 'novo']),
            'type_of_business' => $this->faker->randomElement(['A', 'V']),
            'furnished' => $this->faker->randomElement(['yes', 'no']),
            'country' => $this->faker->country(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'province' => $this->faker->randomElement(['Icolo Bengo', 'Luanda']),
            'location' => ["lat"=>$this->faker->latitude(),"lng" =>$this->faker->longitude()],
            'length' => $this->faker->randomFloat(2, 10, 100),
            'width' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->paragraph(),
            'room' => $this->faker->numberBetween(1, 10),
            'bathroom' => $this->faker->numberBetween(1, 10),
            'useful_sand' => $this->faker->randomFloat(2, 10, 1000),
            'favorite' => $this->faker->boolean(),
            'announces' => $this->faker->boolean(),
            'price' => $this->faker->randomFloat(2, 10000, 1000000),
            'deleted' => false,
        ];
    }
}
