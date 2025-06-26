<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\QrStatistic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QrStatisticTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_qr_statistic_record()
    {
        $statistic = QrStatistic::factory()->create([
            'type' => 'url',
            'ip_address' => '192.168.1.1'
        ]);

        $this->assertDatabaseHas('qr_statistics', [
            'id' => $statistic->id,
            'type' => 'url',
            'ip_address' => '192.168.1.1'
        ]);
    }

    #[Test]
    public function it_can_retrieve_daily_statistics()
    {
        // Создаем записи за разные даты
        QrStatistic::factory()->create(['created_at' => now()->subDays(2)]);
        QrStatistic::factory()->count(3)->create(['created_at' => now()]);
        
        $dailyStats = QrStatistic::selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->assertCount(2, $dailyStats);
        $todayStat = $dailyStats->where('date', now()->format('Y-m-d'))->first();
        $this->assertEquals(3, $todayStat->total);
    }

    #[Test]
    public function it_can_group_statistics_by_type()
    {
        QrStatistic::factory()->count(2)->create(['type' => 'url']);
        QrStatistic::factory()->count(4)->create(['type' => 'text']);
        QrStatistic::factory()->count(1)->create(['type' => 'phone']);

        $typesDistribution = QrStatistic::select('type')
            ->selectRaw('count(*) as total')
            ->groupBy('type')
            ->get();

        $this->assertCount(3, $typesDistribution);
        $this->assertEquals(4, $typesDistribution->where('type', 'text')->first()->total);
    }
}
