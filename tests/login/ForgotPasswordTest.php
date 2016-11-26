<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class ForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

    public function testForgotPasswordWithWrongEmail()
    {
        $this->visit('/')
        ->click('Forgot Your Password?')
            ->type('test@flarepoint.com', 'email')
            ->press('Send Password Reset Link')
            ->see('We can\'t find a user with that e-mail address.');;
    }

    public function testForgotPasswordInputIsEmpty()
    {
        $this->visit('/')
        ->click('Forgot Your Password?')
            ->type('', 'email')
            ->press('Send Password Reset Link')
            ->see('The email field is required.');
    }
}
