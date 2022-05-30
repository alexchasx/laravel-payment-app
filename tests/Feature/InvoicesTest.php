<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoicesTest extends TestCase
{
    use RefreshDatabase;

    // /**
    //  * A basic feature test example.
    //  *
    //  * @return void
    //  */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    /** @test */
    public function test_not_authenticated_users_cant_create_a_new_invoice()
    {
        // подробное описание ошибок
        $this->withoutExceptionHandling([AuthenticationException::class]);

        $user = User::factory()->create();

        $this->get('invoices/new')
            ->assertRedirect('login');
    }

    /** @test */
    public function test_customer_can_see_a_form_for_creating_new_invoice()
    {
        // подробное описание ошибок
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user)->get('invoices/new')
            ->assertStatus(200)
            ->assertSee('Create new Invoice');
    }
}
