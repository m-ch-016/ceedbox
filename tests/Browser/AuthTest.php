<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase {

    use DatabaseMigrations;

    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('/register')
                // ->type('name', 'john appleseed')
                // ->type('email', 'john123@example.com')
                ->type('name', fake()->name())
                ->type('email', fake()->email())
                ->type('password', 'password1234')
                ->type('password_confirmation', 'password1234')
                ->scrollto('.submit')
                ->click('.submit')
                ->assertSee('Job Listings')
                ->click('.logout-button');
        });
    }

    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('/login')
                ->type('email', 'john123@example.com')
                ->type('password', 'password1234')
                ->scrollTo('.submit')
                ->click('.submit')
                ->assertSee('Job Listings');
        });
    }



}
