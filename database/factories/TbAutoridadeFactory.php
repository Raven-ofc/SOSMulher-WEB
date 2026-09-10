<?php

namespace Database\Factories;

use App\Models\tbautoridade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<tbautoridade>
 */
class TbAutoridadeFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'imagemAutoridade' => 'default.jpg',
            'nomeAutoridade' => fake()->name(),
            'emailAutoridade' => fake()->unique()->safeEmail(),
            'cpfAutoridade' => fake()->unique()->numerify('###.###.###-##'),
            'matriculaAutoridade' => now(),
            'cargoAutoridade' => fake()->jobTitle(),
            'unidadeAutoridade' => fake()->company(),
            'senhaAutoridade' => static::$password ??= Hash::make('password'),
            'statusAutoridade' => 'ativo',
            'remember_token' => Str::random(10),
        ];
    }
}