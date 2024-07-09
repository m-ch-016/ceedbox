<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JobTest extends DuskTestCase {
    public function see_jobs()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('/jobs')
                ->assertSee('.job');
        });
    }

    public function see_job_details()
    {
        $this->browse(function (Browser $browser){
            $browser->maximize();
            $browser->visit('/jobs')
                ->click('.job:first-child a')
                ->assertSee('Job');
        });
    }

}
