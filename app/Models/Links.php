<?php
// Объявление пространства имен модели
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Links extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'url_to',
        'url_from'
    ];
    protected $hidden = ['key'];

    // Явно указываем имя таблицы в lowercase
    protected $table = 'links';
}
