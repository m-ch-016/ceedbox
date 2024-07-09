<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JobTest extends DuskTestCase {
    public function test_see_jobs()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('/jobs')
                ->assertSee('.job');
        });
    }

    public function test_see_job_details()
    {
        $this->browse(function (Browser $browser){
            $browser->maximize();
            $browser->visit('/jobs')
                ->click('.job:first-child a')
                ->assertSee('Job');
        });
    }

}
