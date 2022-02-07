<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegisterationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return string[]
     */
    protected function user_data(): array
    {
        return [
            'name' => 'Sadraa',
            'email' => 'Sadraa@test.com',
            'password' => 'password',
        ];
    }

    /**
     * @test
     */
    public function user_can_be_register()
    {
        $user_data = $this->user_data();
        $this->post('/register', array_merge($user_data, ['password_confirmation' => 'password']))
            ->assertSessionHasNoErrors()
            ->assertRedirect('/home');

        $this->assertTrue(Hash::check('password', User::first()->password));
        unset($user_data['password']);
        $this->assertDatabaseHas('users', $user_data);
    }

    /**
     * @test
     */
    public function registeration_form_fields_is_required() //not be empty!
    {
        // Name field is required............
        $this->post('/register', array_merge($this->user_data(), ['name' => '']))
            ->assertSessionHasErrors(['name']);

        // Email field is required...........
        $this->post('/register', array_merge($this->user_data(), ['email' => '']))
            ->assertSessionHasErrors(['email']);

        // Password field is required...........
        $this->post('/register', array_merge($this->user_data(), ['password' => '']))
            ->assertSessionHasErrors(['password']);

        // Password_confirmation field is required...........
        $this->post('/register', array_merge($this->user_data(), ['password_confirmation' => '']))
            ->assertSessionHasErrors(['password']);
    }

    /**
     * @test
     */
    public function password_and_password_confirmation_must_be_the_same()
    {
        // we assume that password is 'password'
        $this->post('/register', array_merge($this->user_data(), ['password_confirmation' => 'passsrwd1']))
            ->assertSessionHasErrors(['password']);

        $this->assertDatabaseMissing('users', $this->user_data());
    }

}
