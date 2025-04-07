<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin {email} {password} {name?}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name') ?? 'Admin User';

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: {$email}");
            return 1;
        }

        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $this->info("User with email {$email} already exists.");
            if (!$this->confirm('Do you want to update this user to admin?')) {
                $this->info('Aborted.');
                return 0;
            }
        } else {
            // Create new user
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $this->info("Creating new admin user: {$email}");
        }
        
        // Set password and admin flag
        $user->password = Hash::make($password);
        $user->admin = true; // Ensure this field exists in your users table
        $user->save();
        
        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Assign role to user
        $user->assignRole('admin');
        
        $this->info("Success! User {$email} is now an admin.");
        return 0;
    }
}