<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Register Interface and Repository in here
        // You must place Interface in first place
        // If you dont, the Repository will not get readed.
        $this->app->bind(
            'App\Interfaces\UserInterface',
            'App\Repositories\UserRepository'
        );
        $this->app->bind(
            "App\Interfaces\ContactsInterface",
            "App\Repositories\ContactsRepository"
        );
        $this->app->bind(
            "App\Interfaces\AppointmentsInterface",
            "App\Repositories\AppointmentsRepository"
        );
    }
}
