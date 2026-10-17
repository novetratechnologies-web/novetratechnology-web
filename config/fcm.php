<?php

return [
    'project_id' => env('FCM_PROJECT_ID'),
    'credentials' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),
    'timeout' => (float) env('FCM_TIMEOUT', 30),
];
