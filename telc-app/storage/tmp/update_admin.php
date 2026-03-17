<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make('Illuminate\\Contracts\\Console\\Kernel');
$kernel->bootstrap();
$hash = app('hash')->make('Password@2026');
app('db')->table('users')->where('email', 'okemajou@yahoo.com')->update([
    'name' => 'Oswald',
    'phone' => '656559879',
    'role' => 'super_admin',
    'is_blocked' => 0,
    'blocked_at' => null,
    'password' => $hash,
    'updated_at' => now(),
]);
$user = app('db')->table('users')->where('email', 'okemajou@yahoo.com')->first();
var_export($user);
