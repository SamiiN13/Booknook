<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create admin user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@booknook.com',
    'password' => Hash::make('password'),
]);

echo "Admin user created successfully!\n";
echo "Email: admin@booknook.com\n";
echo "Password: password\n"; 