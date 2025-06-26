<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QrGenerationTest extends TestCase
{
    use RefreshDatabase; // Очищает базу после каждого теста

    #[Test]
    public function generating_qr_code_creates_statistics_record()
    {
        // 1. Отправляем POST-запрос на генерацию QR-кода
        $response = $this->post('/generate-qr', [
            'type' => 'url',
            'content' => 'https://example.com'
        ]);

        // 2. Проверяем редирект после успешного создания
        $response->assertStatus(302);

        // 3. Проверяем, что запись появилась в базе
        $this->assertDatabaseHas('qr_statistics', [
            'type' => 'url'
        ]);
        
        // 4. Проверяем общее количество записей
        $this->assertDatabaseCount('qr_statistics', 1);
    }

    #[Test]
    public function it_requires_valid_type()
    {
        $response = $this->post('/generate-qr', [
            'type' => 'invalid_type',
            'content' => 'https://example.com'
        ]);

        $response->assertSessionHasErrors('type');
    }
}