<?php
// Объявление пространства имен модели
namespace App\Models;

// Импорт базового класса Model из Eloquent ORM
use Illuminate\Database\Eloquent\Model;

// Объявление класса модели links, который наследует Model
class Links extends Model
{
    // Отключение автоматического управления полями created_at и updated_at
    public $timestamps = false;
}
