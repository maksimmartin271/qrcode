<?php

namespace Tests\Unit;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinksTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test] // Современный способ вместо /** @test */
    public function it_can_create_a_link()
    {
        // Создаем таблицу через миграцию
        $this->artisan('migrate')->run();

        $link = Link::create([
            'url_to' => 'https://example.com',
            'url_from' => 'https://original.com',
        ]);

        $this->assertEquals('https://example.com', $link->url_to);
        $this->assertEquals('https://original.com', $link->url_from);
        $this->assertDatabaseHas('links', [
            'url_to' => 'https://example.com',
            'url_from' => 'https://original.com',
        ]);
    }
}
