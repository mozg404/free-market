<?php

namespace App\Console\Commands;

use App\Data\UserRegisteringData;
use App\Services\Auth\UserRegistrar;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class MakeUser extends Command
{
    protected $signature = 'make:user {name} {email} {password}';

    protected $description = 'Register new user';

    public function handle(UserRegistrar $registrar)
    {
        try {
            $registrar->register(UserRegisteringData::validateAndCreate([
                'name' =>  $this->argument('name'),
                'email' => $this->argument('email'),
                'password' => $this->argument('password'),
            ]));
        } catch (ValidationException $exception) {
            $this->error($exception->getMessage());
        }
    }
}
