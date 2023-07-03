<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = Hash::make($this->argument('password'));

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->error('Admin role not found!');
            return;
        }

        $user->assignRole($adminRole);

        $this->info('Admin user created successfully!');
    }
}