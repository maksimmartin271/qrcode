<?php

// Объявление пространства имен контроллера
namespace App\Http\Controllers;

// Импорт используемых классов
use App\Models\links;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// Объявление класса контроллера для работы с QR-кодами
class QRcodeController extends Controller
{
    // Метод генерации QR-кода из полученных данных
    public function generate(Request $request)
    {
        // Получаем данные из запроса
        $data = $request->input('data');
        $randomNumber = rand(1, 100);

        return response()->json(['data' => (new QRCode) ->render($data), 'key' => sha1($randomNumber)]);

        // Генерация и вывод изображения QR-кода
        //echo (new QRCode)->render($data);
    }

    public function generateImage(Request $request)
    {
        // Получаем данные из запроса
        $data = $request->input('data');
        // Выводим полученные данные
        print($data);

        // Генерация и вывод изображения QR-кода
        echo '<img src="' . (new QRCode)->render($data) . '" alt="QR Code" />';
    }

    // Метод для редиректа и генерации QR-кода по ID ссылки
    public function redirect($id)
    {
        $link = links::find($id);

        if (!$link) {
            // Лучше возвращать HTTP-ответ
            return response("Ссылка не найдена {$id}", 404);
        }

        if (empty($link->url_from)) {
            return response("Некорректный URL", 400);
        }

        // Возвращаем view с QR-кодом вместо echo
        return view('qrcode', [
            'qrCode' => (new QRCode)->render($link->url_from)
        ]);
    }

    // Метод для скачивания/изменения ссылки
    public function edit(Request $request, $id)
    {
        // Поиск ссылки с ID = 3
        $link = Links::find($id);

        // Проверка существования ссылки
        if (!$link) {
            return('Ссылка не найдена'."\n");
        }

        // Вывод текущего URL
        echo $link->url_to;
        echo $link->url_from;
        echo $link->id;

        // Изменение URL на значение '3'
        $url_to = $request->input('url_to');
        if(!empty($url_to)){
            $link->url_to = $url_to;
        }
        print(request()->input('url_to'));

        $url_from = $request->input('url_from');
        if(!empty($url_from)){
            $link->url_from = $url_from;
        }
        print(request()->input('url_from'));

        // Сохранение изменений в базе
        $link->save();
    }

    public function check(){
        $response = Http::withToken(csrf_token()) -> post(url('/create'),['url_to' => 'abcd', 'url_from' => 'qwerty']);
        if($response -> successful()){
            print('ok');
        }
        $id = $response -> body();
        //$response = Http::get(url('/deleted/'.$id));
        print($id);

    }
    public function create(Request $request)
    {
        $validated = $request->validate([
            'url_to' => 'required|string', // Обязательное поле
            'url_from' => 'nullable|string',
        ]);

        $link = links::create($validated);
        $link -> key = sha1($link->id);
        $link -> save();
        $link -> makeVisible(['key']);
        return response()->json($link, 201);

    }

    public function read(){
        return response()->json(Links::all());

    }
}
