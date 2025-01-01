<?php

namespace App\Console\Commands;

use App\Enums\RoleUsers;
use Illuminate\Console\Command;

class MakeFilamentUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filament-user-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates a new user with custom fields.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Name');
    $surname = $this->ask('Surname');
    $email = $this->ask('Email Address');
    $password = $this->secret('Password');
    $phone = $this->ask('Phone');
        if (!is_numeric($phone)) {
            $this->error('Phone must be a numeric value.');
            return;
        }
    $address = $this->ask('Address');
    $bio = $this->ask('Bio');
    $roleIndex = $this->choice('Role', ['admin', 'vet', 'owner']);
    $role = RoleUsers::from($roleIndex)->value;
    /* $role = $this->choice('Role', ['admin', 'vet', 'owner']); */ // Siempre pregunta el rol.
    $img_path = $this->ask('Image Path (press Enter to skip)');

    $user = \App\Models\User::create([
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'password' => bcrypt($password),
        'phone' => $phone,
        'address' => $address,
        'bio' => $bio,
        'role' => $role,
        'img_path' => $img_path,
    ]);

    $this->info("User {$user->name} ({$user->role->value}) created successfully!");
    }
}
