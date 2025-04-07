<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Первичный ключ
            $table->string('username')->unique();  // Имя администратора (уникальное)
            $table->string('password');            // Хэшированный пароль
            $table->enum('role', ['Owner', 'Admin'])->default('Admin'); // Роль (Owner или Admin)
            $table->timestamps(); // Поля для отметки времени создания и обновления
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}

