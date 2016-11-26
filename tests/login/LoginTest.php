<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testLoginWithWrongPassword()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('WrongPassword', 'password')
            ->press('Login')
            ->see('These credentials do not match our records.');
    }

    public function testLoginWithCorrectPassword()
    {
        User::create([
            'name' => 'Casper',
            'email' => 'bottelet@flarepoint.com',
            'password' => bcrypt('admin')
        ]);
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->seePageIs('/');
    }
}
