<?php

namespace Tests\Unit;

use App\Http\Controllers\Controller_22;
use App\Models\links;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Controller_22Test extends TestCase
{
    //use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new Controller_22();
    }

    #[Test]
    public function test_deleted_method()
    {
        $link = links::create([
            'url_to' => 'to_delete',
            'url_from' => 'from_delete'
        ]);

        $this->controller->deleted($link->id);

        $this->assertSoftDeleted('links', [
            'id' => $link->id
        ]);
    }
}
