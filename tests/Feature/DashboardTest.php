<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_not_authenticated_users_can_not_see_dashboard()
    {
        $this->get('/')->assertRedirect('login');
    }

    public function test_it_retrieves_last_3_payments_of_each_type()
    {
    }
}
