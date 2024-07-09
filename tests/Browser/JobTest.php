<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PDO;

class JobTest extends DuskTestCase {
    public function test_see_jobs()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('/jobs')
                ->assertSee('pays');
        });
    }

    public function test_see_job_details()
    {
        $this->browse(function (Browser $browser){
            $browser->maximize();
            $browser->visit('/jobs')
                ->click('.job-listing > a:first-child')
                ->assertSee('Job');
        });
    }

    public function test_create_job()
    {
        $company = fake()->company();


        $this->browse(function (Browser $browser) use ($company){
            $browser->maximize();
            $browser->visit('/login')
                ->type('email', 'john.doe@example.com')
                ->type('password', 'password123')
                ->click('button[type="submit"]')
                ->pause(2000)
                ->visit('/jobs/create')
                ->type('title', 'Test Company')
                ->type('salary', fake()->numberBetween(100,20000))
                ->click('button[type="submit"]')
                ->pause(2000)
                ->visit('/jobs')
                ->pause(2000)
                ->assertSee($company);
        });
    }

}
