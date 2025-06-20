<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\links;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinksTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test] // Современный способ вместо /** @test */
    public function it_can_create_a_link()
    {
        // Создаем таблицу через миграцию
        $this->artisan('migrate')->run();

        $link = links::create([
            'url_to' => 'https://example.com',
            'url_from' => 'https://original.com'
        ]);

        $this->assertEquals('https://example.com', $link->url_to);
        $this->assertEquals('https://original.com', $link->url_from);
        $this->assertDatabaseHas('links', [
            'url_to' => 'https://example.com',
            'url_from' => 'https://original.com'
        ]);
    }
}
