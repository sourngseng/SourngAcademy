<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$cols = DB::select('SHOW COLUMNS FROM invoices');
foreach ($cols as $c) {
    echo $c->Field . ' | ' . $c->Type . "\n";
}
