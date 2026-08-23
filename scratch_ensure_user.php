<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'ariscapramalistyo@gmail.com')->first();
if (!$user) {
    App\Models\User::create([
        'name' => 'Arisca Pramalistyo',
        'email' => 'ariscapramalistyo@gmail.com',
        'password' => bcrypt('12345678'),
        'phone' => '081234567890',
        'email_verified_at' => now(),
    ]);
    echo "USER CREATED\n";
} else {
    $user->update([
        'password' => bcrypt('12345678'),
        'email_verified_at' => now(),
    ]);
    echo "USER UPDATED\n";
}
