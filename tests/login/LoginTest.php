<?php

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
     //Create a user for loggin in
        $user = new User;
        $user->name = 'Casper';
        $user->email = 'bottelet@flarepoint.com';
        $user->password = bcrypt('admin');
        $user->save();

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->seePageIs('/');
    }
}
