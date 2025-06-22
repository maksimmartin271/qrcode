<?php

// Объявление пространства имен контроллера

namespace App\Http\Controllers;

// Импорт используемых классов
use App\Models\Link;
use App\Models\Links;
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

        return response()->json(['data' => (new QRCode()) ->render($data), 'key' => sha1($randomNumber)]);

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
        echo '<img src="' . (new QRCode())->render($data) . '" alt="QR Code" />';
    }

    // Метод для редиректа и генерации QR-кода по ID ссылки
    public function redirect($id)
    {

        $link = links::where('url_from', $id)->first();
        //$link = links::where('url_from', $id)->take(1)->get();

        if (! $link) {
            // Лучше возвращать HTTP-ответ
            $link = links::find((int)$id);
        }


        if (! $link) {
            // Лучше возвращать HTTP-ответ
            return response("Ссылка не найдена {$id}", 404);
        }

        if (empty($link->url_to)) {
            //if (empty($link[0]->url_to)) {
            return response()->json($link);

            return response("Некорректный URL", 400);
        }

        return redirect($link->url_to);

    }

    // Метод для скачивания/изменения ссылки
    public function edit(Request $request, $id)
    {
        $link = Link::find($id);

        if (! $link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        if ($link->key !== $request->input('key')) {
            return response()->json(['error' => 'Invalid key'], 403);
        }

        $link->update([
            'url_to' => $request->input('url_to', $link->url_to),
            'url_from' => $request->input('url_from', $link->url_from),
        ]);

        return response()->json($link);
    }

    public function check()
    {
        $response = Http::withToken(csrf_token()) -> post(url('/create'), ['url_to' => 'abcd', 'url_from' => 'qwerty']);
        if ($response -> successful()) {
            print('ok');
        }
        $id = $response -> body();
        //$response = Http::get(url('/deleted/'.$id));
        print($id);

    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'url_to' => 'required|string',
            'url_from' => 'nullable|string',
            'edit_key_hash' => 'required|string', // Добавляем валидацию
        ]);

        $link = Link::create($validated);
        $link->key = sha1($link->id);
        $link->save();
        $link->makeVisible(['key']);

        return response()->json($link, 201);
    }

    public function deleted($id)
    {
        $link = Link::find($id);

        if (! $link) {
            return response()->json(['error' => 'Not found'], 404);
        }

        if ($link->key !== request()->input('key')) {
            return response()->json(['error' => 'Invalid key'], 403);
        }

        $link->delete();

        return response()->json(['success' => true]);
    }

    public function read()
    {
        return response()->json(Link::withTrashed()->get());
    }

    public function UnDeleted($id)
    {
        $links = links::withTrashed()->find($id);
        $deleted = $links->restore();
    }
}
