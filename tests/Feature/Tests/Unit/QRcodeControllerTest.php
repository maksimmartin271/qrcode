<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\QRcodeController;
use App\Models\links;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Http\Response;

class QRcodeControllerTest extends TestCase
{
    //use RefreshDatabase; // Важно для изоляции тестов

    protected $controller;

    protected function setUp(): void //вызывается перед каждым тестом для подготовки
    {
        parent::setUp();
        $this->controller = new QRcodeController();
    }

    #[Test]
    public function test_create_method()
    {
        // Явно передаём обязательные поля
        $request = new Request([
            'url_to' => 'https://required-value.com', // Обязательное поле
            'url_from' => 'https://optional-value.com'
        ]);

        // Для отладки
        logger()->debug('Test data', $request->all());

        $this->controller->create($request);

        // Проверяем только обязательное поле
        $this->assertDatabaseHas('links', [
            'url_to' => 'https://required-value.com'
        ]);
    }

    #[Test]
    public function test_edit_method()
    {
        $link = links::create([
            'url_to' => 'old_to',
            'url_from' => 'old_from'
        ]);

        $request = new Request([
            'url_to' => 'new_to',
            'url_from' => 'new_from'
        ]);

        $this->controller->edit($request, $link->id);

        $updatedLink = links::find($link->id);
        $this->assertEquals('new_to', $updatedLink->url_to);
        $this->assertEquals('new_from', $updatedLink->url_from);
    }

    #[Test]
    public function test_read_method()
    {
        // Создадим и удалим запись для тестирования мягкого удаления
        $link = links::create([
            'url_to' => 'to_delete',
            'url_from' => 'from_delete'
        ]);
        $link->delete();

        $response = $this->controller->read();

        $this->assertCount(1, $response->getData());
        $this->assertEquals('to_delete', $response->getData()[0]->url_to);
    }
}
