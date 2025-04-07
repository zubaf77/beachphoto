<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3), // Генерируем название альбома
            'description' => $this->faker->paragraph, // Генерируем описание альбома
            'views' => $this->faker->numberBetween(0, 1000), // Генерируем количество просмотров
            'delete_after_views' => $this->faker->numberBetween(100, 1000), // Генерируем лимит на удаления после просмотров
            'password' => $this->faker->password, // Генерируем пароль для альбома
            'secure_token' => Str::random(32), // Устанавливаем токен
            'ip_address' => $this->faker->ipv4, // Генерируем IP-адрес
        ];
    }
}
