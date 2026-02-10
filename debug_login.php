<?php
// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check User
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::withoutGlobalScope('office_access')->where('email', 'admin@gmail.com')->first();
if ($user) {
    echo "USER FOUND\n";
    echo "ID: " . $user->id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Status: " . ($user->status ? 'Active' : 'Inactive') . "\n";
    echo "Role ID: " . $user->role_id . "\n";
    echo "Kantor ID: " . ($user->kantor_id ?? 'NULL') . "\n";
    echo "Password Check: " . (Hash::check('password', $user->password) ? 'MATCH' : 'FAIL') . "\n";

    if ($user->role) {
        echo "Role Name: " . $user->role->role_name . "\n";
    } else {
        echo "Role Relation: NULL\n";
    }
} else {
    echo "USER NOT FOUND\n";
}
