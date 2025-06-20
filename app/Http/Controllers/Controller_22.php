<?php

// Объявление пространства имен для контроллера
namespace App\Http\Controllers;

// Импорт необходимых классов
use App\Models\links;
use App\Models\links_2;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\Request;


// Объявление класса контроллера
class Controller_22 extends Controller
{
    // Метод для получения данных профиля
    public function profile()
    {
        // Получение имени текущего маршрута
        $route = request()->route()->getName();
        // Если маршрут  'l1'
        if ($route == 'l1') {
            // Возвращаем все записи из таблицы links в формате JSON
            return response()->json(links::all());
        }
        // Иначе возвращаем все записи из таблицы links_2 в формате JSON
        return response()->json(links_2::all());
    }

    // Метод для генерации QR-кода
    public function profile2(Request $request)
    {
        // Перебор всех записей из таблицы links_2
        foreach (links_2::all() as $link) {
            // Получение данных из запроса
            $data = $request->input('data');
            // Вывод данных
            print($data);
            // Генерация и вывод QR-кода
            echo '<img src="' . (new QRCode)->render($data) . '" alt="QR Code" />';
        }
    }

    // Метод  редиректа
    public function wrong(Request $request)
    {
        // Перенаправление на маршрут '/correct'
        return redirect('/correct');
    }

    // Метод для корректного ответа
    public function correct(Request $request)
    {
        // Возвращаем строку 'ccc'
        return 'ccc';
    }

    // Метод для редиректа по ID
    public function redirect($id)
    {
        // Поиск записи в таблице links по ID
        $link = links::find($id);

        // Если запись не найдена
        if (!$link) {
            // Вывод сообщения об ошибке
            echo('Ссылка не найдена' . $id . "\n");
        }

        // Если поле url_to пустое
        if (empty($link->url_to)) {
            // Возвращаем ошибку 400
            return response("Некорректный URL", 400);
        }

        // Редирект по URL из найденной записи
        return redirect($link->url_to);
    }

    // Метод для удаления записи
    public function deleted($id){
        // Поиск и удаление записи по ID
        $deleted = links::find($id)->delete();
    }
    public function testJson(){
        return response()->json(['abcd' => 'qwerty', 'dbca' => 'ytrewq']);
    }
}

