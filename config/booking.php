<?php

return [
    // Lama waktu booking berstatus pending sebelum otomatis dibatalkan (dalam menit)
    // Default: 1440 menit = 1 x 24 jam
    'pending_expiry_minutes' => env('BOOKING_PENDING_EXPIRY_MINUTES', 1440),
];
