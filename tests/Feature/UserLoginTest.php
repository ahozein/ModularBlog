<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    private $authenticated_user;
    private $request_data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request_data = [
            'email' => 'sadra@test.com',
            'password' => 'password'
        ];
        $this->authenticated_user = User::factory(array_merge(
            $this->request_data, ['password' => Hash::make('password')]
        ))->create();
    }

    /**
     * @test
     */
    public function login_screen_can_be_rendered()
    {
        $this->get('/login')
            ->assertOk();
    }

    /**
     * @test
     */
    public function login_form_fields_is_required() // not be empty!
    {
        //Email field is required.................
        $this->post('/login', array_merge($this->request_data, ['email' => '']))
            ->assertSessionHasErrors(['email']);

        //Password field is required.................
        $this->post('/login', array_merge($this->request_data, ['password' => '']))
            ->assertSessionHasErrors(['password']);
    }

    /**
     * @test
     */
    public function if_email_dosent_match_with_database_records_user_can_not_authenticate()
    {
        $this->post('/login', array_merge($this->request_data, ['email' => 'abcd@test.com']))
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    /**
     * @test
     */
    public function if_password_dosent_match_with_database_records_user_can_not_authenticate()
    {
        $this->post('/login', array_merge($this->request_data, ['password' => 'pasord1']))
            ->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * @test
     */
    public function user_can_authenticate() //Can be login!
    {
        $this->post('/login', $this->request_data)
            ->assertSessionHasNoErrors();

        $this->assertAuthenticated();
    }

    /**
     * @test
     */
    public function user_can_logout()
    {
        //First of all Login user.............
        $this->post('/login', $this->request_data);
        $this->assertAuthenticated();

        //Now Logout!
        $this->actingAs($this->authenticated_user)->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
