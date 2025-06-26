<?php

namespace Tests\Unit;

use App\Http\Controllers\QRcodeController;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class QRcodeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new QRcodeController();
    }

    #[Test]
    public function test_create_method()
    {
        $request = new Request([
            'url_to' => 'https://example.com',
            'url_from' => 'custom123',
            'edit_key_hash' => 'hash123', // если поле обязательно
        ]);

        $response = $this->controller->create($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('links', [
            'url_to' => 'https://example.com',
            'url_from' => 'custom123',
        ]);
    }

    #[Test]
    public function test_edit_method()
    {
        $link = Link::create([
            'url_to' => 'old_to',
            'url_from' => 'old_from',
            'key' => 'test_key',
        ]);

        $request = new Request([
            'url_to' => 'new_to',
            'url_from' => 'new_from',
            'key' => 'test_key',
        ]);

        $response = $this->controller->edit($request, $link->id);

        $this->assertEquals(200, $response->getStatusCode());

        $updatedLink = Link::find($link->id);
        $this->assertEquals('new_to', $updatedLink->url_to);
        $this->assertEquals('new_from', $updatedLink->url_from);
    }

    #[Test]
    public function test_read_method()
    {
        // Сначала создаем активную запись
        $activeLink = Link::create([
            'url_to' => 'active',
            'url_from' => 'test',
            'key' => 'key1',
            'edit_key_hash' => 'hash1',
        ]);

        // Затем создаем и удаляем вторую запись
        $deletedLink = Link::create([
            'url_to' => 'deleted',
            'url_from' => 'test',
            'key' => 'key2',
            'edit_key_hash' => 'hash2',
        ]);
        $deletedLink->delete();

        $response = $this->controller->read();

        $data = $response->getData();
        $this->assertCount(2, $data);

        // Проверяем наличие обеих записей без учета порядка
        $urls = array_column($data, 'url_to');
        $this->assertContains('active', $urls);
        $this->assertContains('deleted', $urls);
    }

    #[Test]
    public function test_deleted_method()
    {
        $link = Link::create([
            'url_to' => 'to_delete',
            'key' => 'valid_key',
            'edit_key_hash' => 'hash123',
        ]);

        // Создаем mock запроса
        $request = new Request([
            'key' => 'valid_key',
        ]);
        $this->app->instance('request', $request);

        $response = $this->controller->deleted($link->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSoftDeleted('links', [
            'id' => $link->id,
        ]);
    }
}
