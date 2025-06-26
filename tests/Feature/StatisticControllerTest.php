<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\QrStatistic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatisticControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function statistics_page_loads_successfully()
    {
        $response = $this->get('/statistics');

        $response->assertStatus(200);
        $response->assertViewIs('statistics.index');
    }

    /** @test */
    public function statistics_page_shows_correct_data()
    {
        // Создаем тестовые данные
        QrStatistic::factory()->count(5)->create();
        
        $response = $this->get('/statistics');
        
        $response->assertViewHas('totalQRCodes', 5);
        $response->assertViewHas('typesDistribution');
        $response->assertViewHas('dailyStats');
    }

    /** @test */
    public function statistics_page_shows_types_distribution_correctly()
    {
        QrStatistic::factory()->count(3)->create(['type' => 'url']);
        QrStatistic::factory()->count(2)->create(['type' => 'text']);

        $response = $this->get('/statistics');
        
        $typesDistribution = $response->viewData('typesDistribution');
        
        $urlType = $typesDistribution->where('type', 'url')->first();
        $this->assertEquals(3, $urlType->total);
        
        $textType = $typesDistribution->where('type', 'text')->first();
        $this->assertEquals(2, $textType->total);
    }

    /** @test */
    public function statistics_page_shows_empty_state_when_no_data()
    {
        $response = $this->get('/statistics');
        
        $response->assertViewHas('totalQRCodes', 0);
        $response->assertSeeText('Нет данных для отображения');
    }
}