<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use PDO;

class JobTest extends DuskTestCase {
    use DatabaseMigrations;

    public function test_see_jobs()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=DatabaseSeeder');

        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('/jobs')
                ->assertSee('pays');
        });
    }

    public function test_see_job_details()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=DatabaseSeeder');

        $this->browse(function (Browser $browser){
            $browser->maximize();
            $browser->visit('/jobs')
                ->click('.job-listing > a:first-child')
                ->assertSee('Job');
        });
    }

    public function test_create_job()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=DatabaseSeeder');

        $company = fake()->company();


        $this->browse(function (Browser $browser) use ($company){
            $browser->maximize();
            $browser->visit('/register')
                ->type('name', 'John Appleseed')
                ->type('email', 'John.Appleseed@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->scrollto('.submit')
                ->click('.submit')
                ->visit('/')
                // ->pause(10000000000)
                ->click('.logout-button')
                ->visit('/login')
                ->type('email', 'John.Appleseed@example.com')
                ->type('password', 'password123')
                ->click('button[type="submit"]')
                ->visit('/jobs/create')
                ->type('title', $company)
                ->type('salary', fake()->numberBetween(100,20000))
                ->click('.save-job')
                ->visit('/jobs')
                ->waitForText($company, 100000);
        });
    }

    public function test_edit_job()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=DatabaseSeeder');

        $company = fake()->company();
        $oldSalary = fake()->numberBetween(100, 20000);
        $newSalary = fake()->numberBetween(100, 20000);

        $this->browse(function (Browser $browser) use ($company, $oldSalary, $newSalary) {
            $browser->maximize();
            $browser->visit('/register')
                    ->type('name', 'John Appleseed')
                    ->type('email', 'John.Appleseed@example.com')
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->scrollto('.submit')
                    ->click('.submit')
                    ->visit('/')
                    ->click('.logout-button')
                    ->visit('/login')
                    ->type('email', 'John.Appleseed@example.com')
                    ->type('password', 'password123')
                    ->click('button[type="submit"]')
                    ->visit('/jobs/create')
                    ->type('title', $company)
                    ->type('salary', $oldSalary)
                    ->click('.save-job')
                    ->visit('/jobs')
                    ->assertSee($company)
                    ->click('.job-listing > a:first-child')
                    ->click('.edit-button')
                    ->clear('salary')
                    ->type('salary', $newSalary)
                    ->click('.update-button')
                    ->assertSee($newSalary);
        });
    }

    public function test_delete_job() {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=DatabaseSeeder');

        $company = fake()->company();


        $this->browse(function (Browser $browser) use ($company){
            $browser->maximize();
            $browser->visit('/register')
                ->type('name', 'John Appleseed')
                ->type('email', 'John.Appleseed@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->scrollto('.submit')
                ->click('.submit')
                ->visit('/')
                ->click('.logout-button')
                ->visit('/login')
                ->type('email', 'John.Appleseed@example.com')
                ->type('password', 'password123')
                ->click('button[type="submit"]')
                ->visit('/jobs/create')
                ->type('title', $company)
                ->type('salary', fake()->numberBetween(100,20000))
                ->click('.save-job')
                ->visit('/jobs')
                ->assertSee($company)
                ->click('.job-listing > a:first-child')
                // ->pause(3000)
                ->click('.edit-button')
                ->click('button[form="delete-form"]')
                ->assertDontSee($company);
        });
    }

}
