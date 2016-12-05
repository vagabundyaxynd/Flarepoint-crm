<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setup();
        App::setLocale('en');
    }


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
        $this->createUser();
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->seePageIs('/');
    }
}
