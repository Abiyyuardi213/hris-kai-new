<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@gmail.com')->first();
if ($user) {
    echo "User Found: ID={$user->id}, Status={$user->status}, Role={$user->role_id}, Kantor={$user->kantor_id}\n";
    echo "Is Password 'password' Correct? " . (Hash::check('password', $user->password) ? 'Yes' : 'No') . "\n";
} else {
    echo "User Not Found\n";
}
