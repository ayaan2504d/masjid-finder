<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_load(): void
    {
        $this->seed();

        $this->get(route('home'))->assertOk();
        $this->get(route('masjids.index'))->assertOk();
        $this->get(route('map'))->assertOk();
        $this->get(route('contact'))->assertOk();
        $this->get(route('timings.index'))->assertOk();
        $this->get(route('admin.dashboard'))->assertOk();
    }
}
