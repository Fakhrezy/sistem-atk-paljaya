<?php

use Illuminate\Database\Eloquent\Casts\Json;

header('Content-Type: application/json');

// Example data for chart testing
$data = [
    'TW' => [
        [
            'label' => 'Q1, Q2, Q3, Q4',
            'data' => [2025, 2026, 2027, 2028],
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'borderColor' => 'rgba(75, 192, 192, 1)',

        ]
    ],
];

function logDebugInfo($message){
    error_log("[DEBUG] $message");
}
logDebugInfo("Debug chart data generated");

function logDebugData($data){
    error_log("[DEBUG DATA]". Json::encode($data));
}

logDebugData($data);


echo json_encode($data);

