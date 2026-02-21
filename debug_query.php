<?php

use App\Models\User;

try {
    $query = User::with('guestProfile')
        ->where('role', 'guest')
        ->withCount('bookings')
        ->withSum(['bookings' => function($q) {
            $q->where('payment_status', 'paid');
        }], 'total_price');
        
    dump($query->first());
    echo "Query executed successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
