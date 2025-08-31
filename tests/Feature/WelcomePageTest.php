<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    /** @test */
    public function welcome_page_returns_view_with_products()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }
}
