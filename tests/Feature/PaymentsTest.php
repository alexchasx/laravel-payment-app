<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentsTest extends TestCase
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
    public function test_not_authenticated_users_cant_create_a_new_payment()
    {
        // подробное описание ошибок
        $this->withoutExceptionHandling([AuthenticationException::class]);

        $user = User::factory()->create();

        $this->get('payments/new')
            ->assertRedirect('login');
    }

    /** @test */
    public function test_customer_can_see_a_form_for_creating_new_payment()
    {
        // подробное описание ошибок
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user)->get('payments/new')
            ->assertStatus(200)
            ->assertSee('Create new Payment');
    }

    /** @test */
    public function test_not_logged_in_user_cant_create_a_new_payment()
    {
        $response = $this->json('post', 'payments', [
            'email' => 'bradle@cooper.com',
            'amount' => 4000, // деньги храним в целых числах
            'currency' => 'usd',
            'name' => 'Boby Baskov',
            'description' => 'Many description',
            'message' => 'Many go go',
        ]);

        $response->assertStatus(401);

        $this->assertEquals(0, Payment::count());
    }

    /** @test */
    public function test_user_can_create_a_new_payment()
    {
        // подробное описание ошибок
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)  // указ. текущ. аутентиф-го польз-ля
            ->json('post', 'payments', [
                'email' => 'bradle@cooper.com',
                'amount' => 4000, // деньги храним в целых числах
                'currency' => 'usd',
                'name' => 'Boby Baskov',
                'description' => 'Many description',
                'message' => 'Many go go',
            ]);

        $response->assertStatus(200);

        $this->assertEquals(1, Payment::count());
        tap(Payment::first(), function ($payment) use ($user) {
            $this->assertEquals($user->id, $payment->user_id);
            $this->assertEquals('bradle@cooper.com', $payment->email);
            $this->assertEquals(4000, $payment->amount);
            $this->assertEquals('usd', $payment->currency);
            $this->assertEquals('Boby Baskov', $payment->name);
            $this->assertEquals('Many description', $payment->description);
            $this->assertEquals('Many go go', $payment->message);
        });
    }

    /** @test */
    public function test_email_field_is_required_to_create_a_payment()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('post', 'payments', [
            // 'email' => 'bradle@cooper.com',
            'amount' => 4000, // деньги храним в целых числах
            'currency' => 'usd',
            'name' => 'Boby Baskov',
            'description' => 'Many description',
            'message' => 'Many go go',
        ]);

        $response->assertStatus(422);

        $this->assertEquals(0, Payment::count());

        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function test_email_field_should_be_a_valid_email_to_create_a_payment()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('post', 'payments', [
            'email' => 'not-valid-email',
            'amount' => 4000, // деньги храним в целых числах
            'currency' => 'usd',
            'name' => 'Boby Baskov',
            'description' => 'Many description',
            'message' => 'Many go go',
        ]);

        $response->assertStatus(422);

        $this->assertEquals(0, Payment::count());

        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function test_amount_field_should_be_integer_to_create_a_payment()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('post', 'payments', [
            'email' => 'not-valid-email',
            'amount' => 'some-amount',
            'currency' => 'usd',
            'name' => 'Boby Baskov',
            'description' => 'Many description',
            'message' => 'Many go go',
        ]);

        $response->assertStatus(422);

        $this->assertEquals(0, Payment::count());

        $response->assertJsonValidationErrors('amount');
    }
}
